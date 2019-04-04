<style>
body {
  font-family: Nunito,sans-serif;
}
.page-break {
    page-break-after: always;
}

.main-section h3{
	font-size: 26px;
    text-align: center;
    margin-top: 30px;
}
.main-section .sub-section h3{
	font-size: 23px;
    text-align: left;
    margin-top: 0px;
}

.main-section p{
	font-size: 16px;
}
.main-section .sub-section p{
	font-size: 14px;
}
li {
  margin-bottom: 15px;
}
li > p{
  margin: 0;
  padding: 0;
  text-indent: 0px;
}

p{
    text-align: justify;
    text-indent: 50px;
}

.chapter-name .heading {
  position: relative;
  margin-top: 30%;
  padding-right: 100px;
  text-align: right;
  max-width: 550px;
  margin-left: auto;
}
.chapter-name .heading p {
  text-align: right;
  font-size: 45px;
  font-weight: 700;
}
.chapter-name .heading p:first-child {
  font-size: 55px;
  margin-bottom: 25px;
}
.main-section table, .sub-section table {
    border: none;
    border-collapse: collapse;
    margin-bottom: 1rem;
}
.main-section table thead tr th, .sub-section table thead tr th, .main-section table thead tr td, .sub-section table thead tr td {
    font-size: 14px;
    border: 1px solid #000;
    padding: 5px 10px;
    font-weight: bold;
}
.main-section table tr td, .sub-section table tr td {
    font-size: 14px;
    border: 1px solid #000;
    padding: 5px 10px;
}
</style>

<div class="chapter-view">
   <div class="chapter-content">
    <div class="chapter-name">
      <div class="heading page-break">
        <p>{{$chapter->id}}</p>
        <p>{{$chapter->name}}</p>
      </div>
     </div>
     <div class="chapter-text">
      <div id="chapterViewText">
      {!! $chapter->text !!}
      </div>
     </div>
   </div>
 </div>
<div class="page-break"></div>
