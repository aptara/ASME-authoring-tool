<?php

namespace App\Modules\Xml\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Modules\Chapter\Chapter;
use Barryvdh\DomPDF\Facade as PDF;
use DB;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Storage;

class XmlController extends Controller
{
    public function uploadXML()
    {
        return view('xml.upload-xml');
    }

    /**
     * @param $xml string Your XML
     * @param $old string Name of the old tag
     * @param $new string Name of the new tag
     * @return string New XML
     */
    function renameTags($xml, $old, $new)
    {
        $dom = new DOMDocument;
        @$dom->loadXML($xml);
        $domPre = $dom->getElementsByTagName($old);
        $length = $domPre->length;

        $domId = $dom->getElementsByTagName('book-part');
        $nodeId = $domId->item(0);
        $chapterID = $nodeId->getAttribute('id');
        $chapterID = str_replace('ch', '', $chapterID);

        For ($i = $length - 1; $i > -1; $i--) {
            $nodePre = $domPre->item($i);

            if ($old == "list") {
                $new = "ul";
                if ($nodePre->getAttribute('list-type') == "simple") {
                    $new = "ol";

                    $matchingElements = $nodePre->getElementsByTagName('label');
                    $totalMatches = $matchingElements->length;
                    $elementsToDelete = array();
                    for ($j = 0; $j < $totalMatches; $j++) {
                        $elementsToDelete[] = $matchingElements->item($j);
                    }
                    foreach ($elementsToDelete as $elementToDelete) {
                        $elementToDelete->parentNode->removeChild($elementToDelete);
                    }
                }
            }

            if ($old == 'sec') {
                $secId = $nodePre->getAttribute('id');
                $secIdLen = count(explode("_", $secId));
                if ($secIdLen == 3) {
                    $nodePre->setAttribute("class", "sub-section");
                } else {
                    if ($secIdLen === 2) {
                        $nodePre->setAttribute("class", "main-section");
                    }
                }
            }

            $nodeDiv = $dom->createElement($new);

            if ($old == 'ul') {
                $nodeDiv->setAttribute('list-type', 'bullet');
            } elseif ($old == 'ol') {
                $nodeDiv->setAttribute('list-type', 'simple');
            }

            foreach ($nodePre->attributes as $attribute) {
                $nodeDiv->setAttribute($attribute->name, $attribute->value);
            }

            // Copy all children into a basic array to avoid an iterator
            // on a changing tree
            $children = iterator_to_array($nodePre->childNodes);
            foreach ($children as $child) {
                $nodeDiv->appendChild($child);
            }
            $nodePre->parentNode->replaceChild($nodeDiv, $nodePre);
        }

        /* section id attribute creation */
        if ($old == 'div') {
            $domPre = $dom->getElementsByTagName('sec');
            $length = $domPre->length;
            $mainSectionCount = 0;

            For ($i = 0; $i < $length; $i++) {
                $nodePre = $domPre->item($i);

                if ($nodePre->getAttribute('class') == 'main-section') {
                    $mainSectionCount++;
                    $id = 'sec' . $chapterID . '_' . $mainSectionCount;
                    $nodePre->setAttribute('id', $id);
                    $subsectionCount = 1;
                } else {
                    if ($nodePre->getAttribute('class') == 'sub-section') {
                        $id = 'sec' . $chapterID . '_' . $mainSectionCount . '_' . $subsectionCount;
                        $nodePre->setAttribute('id', $id);
                        $subsectionCount++;
                    }
                }
                $nodePre->removeAttribute('class');
            }
        }

        /* label to list-item simple type */
        if ($old == 'ol') {
            $domPre = $dom->getElementsByTagName('list');
            $length = $domPre->length;
            For ($i = 0; $i < $length; $i++) {
                $nodePre = $domPre->item($i);
                $listType = $nodePre->getAttribute('list-type');
                if ($listType == 'simple') {
                    $liElements = $nodePre->getElementsByTagName('li');
                    $liLength = $liElements->length;
                    For ($j = 0; $j < $liLength; $j++) {
                        $liItem = $liElements->item($j);

                        $nodeLabel = $dom->createElement('label');
                        $newText = $dom->createTextNode($j + 1);
                        $nodeLabel->appendChild($newText);

                        $labelElements = $liItem->getElementsByTagName('label');
                        $labelLength = $labelElements->length;
                        if ($labelLength > 0) {
                            $labelItem = $labelElements->item(0);
                            $liItem->replaceChild($nodeLabel, $labelItem);
                        } else {
                            $liItem->appendChild($nodeLabel);
                        }
                    }
                }
            }
        }

        return $dom->saveXML();
    }

    public function submitXML(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileToUpload' => 'required',
        ]);
        $newText = '';
        $chapterTitle = '';


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('fileToUpload')) {
            $path = $request->file('fileToUpload')->store('XML');

            $xml = Storage::get($path);
            $decoded = html_entity_decode($xml);
            $xml = simplexml_load_string($decoded);

            $new = $this->renameTags($xml->asXML(), "sec", "div");
            $new = $this->renameTags($new, "list", "ul");
            $new = $this->renameTags($new, "list-item", "li");
            $new = $this->renameTags($new, "title", "h3");
            $new = $this->renameTags($new, "italic", "em");

            $xml = simplexml_load_string($new);
            foreach ($xml->children() as $firstChild) {
                if ($firstChild->getName() === 'book-part') {
                    foreach ($firstChild->children() as $secondChild) {
                        if ($secondChild->getName() === 'body') {
                            $newText = $secondChild->asXML();
                        }
                        if ($secondChild->getName() === 'book-part-meta') {
                            foreach ($secondChild->children() as $thirdChild) {
                                if ($thirdChild->getName() === 'title-group') {
                                    foreach ($thirdChild->children() as $fourthChild) {
                                        if ($fourthChild->getName() === 'h3') {
                                            $chapterTitle = $fourthChild;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (strlen($newText) > 0 && strlen($chapterTitle) > 0) {
                $chapter = new Chapter();
                $chapter->text = $newText;
                $chapter->name = $chapterTitle;
                $chapter->save();
                return redirect()->route('chapter-list');
            } else {
                return redirect()->back()->with('error', 'Sorry file is not according to the format.');
            }
        } else {
            return redirect()->back()->with('error', 'Sorry file is not uploaded.');
        }
    }

    public function downloadXML()
    {
        $chapters = Chapter::all();
        return view('xml.download-xml', compact('chapters'));
    }

    public function downloadXMLFile($cid)
    {
        $chapter = Chapter::find($cid);
        $text = $chapter->text;
        $fileName = 'Chapter-' . $cid;

        $headerFilePath = public_path('XML/xmlFileHeader.xml');
        $headeText = file_get_contents($headerFilePath);

        $bookPart = '<book-part book-part-type="chapter" id="ch' . $cid . '">
        <book-part-meta>
        <book-part-id>861868_ch' . $cid . '</book-part-id>
        <book-part-id book-part-id-type="doi">10.1115/1.861868_ch' . $cid . '</book-part-id>
        <title-group>
        <label>' . $cid . '</label>
        <title>' . $chapter->name . '</title>
        </title-group>
        </book-part-meta>';

        $xml = $headeText . $bookPart . '<body>' . $text . '</body></book-part></book-part-wrapper>';

        $decoded = html_entity_decode($xml);
        $xml = simplexml_load_string($decoded);

        $new = $this->renameTags($xml->asXML(), "div", "sec");
        $new = $this->renameTags($new, "ul", "list");
        $new = $this->renameTags($new, "ol", "list");
        $new = $this->renameTags($new, "li", "list-item");
        $new = $this->renameTags($new, "h3", "title");
        $new = $this->renameTags($new, "em", "italic");

        $xml = simplexml_load_string($new);

        header('Content-type: text/xml');
        header('Content-Disposition: attachment; filename="' . $fileName . '.xml"');
        echo $new;
        exit();
    }

    public function downloadAllXML()
    {

        $headerFilePath = public_path('XML/xmlFileHeader.xml');
        $headeText = file_get_contents($headerFilePath);

        $chapters = Chapter::all();
        $xml = $headeText;
        foreach ($chapters as $chapter) {
            $cid = $chapter->id;
            $chapter = Chapter::find($cid);
            $text = $chapter->text;
            $fileName = 'Chapter-' . $cid;

            $bookPart = '<book-part book-part-type="chapter" id="ch' . $cid . '">
        <book-part-meta>
        <book-part-id>861868_ch' . $cid . '</book-part-id>
        <book-part-id book-part-id-type="doi">10.1115/1.861868_ch' . $cid . '</book-part-id>
        <title-group>
        <label>' . $cid . '</label>
        <title>' . $chapter->name . '</title>
        </title-group>
        </book-part-meta>';

            $xml .= $bookPart . '<body>' . $text . '</body></book-part>';

//            $xml = simplexml_load_string($new);

        }
        $xml .= '</book-part-wrapper>';

        $decoded = html_entity_decode($xml);
        $xml = simplexml_load_string($decoded);

        $new = $this->renameTags($xml->asXML(), "div", "sec");
        $new = $this->renameTags($new, "ul", "list");
        $new = $this->renameTags($new, "ol", "list");
        $new = $this->renameTags($new, "li", "list-item");
        $new = $this->renameTags($new, "h3", "title");
        $new = $this->renameTags($new, "em", "italic");


        header('Content-type: text/xml');
        header('Content-Disposition: attachment; filename="All_Chapters.xml"');
        echo $new;
        exit();
    }

    public function downloadPDFFile($cid)
    {
        $chapter = Chapter::find($cid);
        $fileName = 'Chapter-' . $cid . '.pdf';
        $pdf = PDF::loadView('chapter.chapter-pdf-view', compact('chapter'));
        return $pdf->download($fileName);
    }

    public function downloadAllPdf()
    {
        $chapters = Chapter::all();
        $html = "";
        foreach ($chapters as $chapter) {
            $cid = $chapter->id;
            $chapter = Chapter::find($cid);
            $view = View::make('chapter.chapter-pdf-view', compact('chapter'));
            $html .= $view->render();
        }
        return PDF::loadHTML($html)->download('All_Chapters.pdf');
    }

}
