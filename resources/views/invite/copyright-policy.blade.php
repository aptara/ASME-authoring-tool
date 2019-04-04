@extends('layouts.app')

@section('content')
<div class="container">
    <div class="invite-form">
        <h2>ASME Copyright Policy</h2>
        <form  method="POST" action="{{route('contributor-copyright')}}">
            {{csrf_field()}}
            <div class="form-group">
                <p>The undersigned hereby assigns irrevocably to ASME all worldwide rights under copyright for this Contribution.</p>
                <p>Contributors retain all proprietary rights in any idea, process, procedure, or articles of manufacture described in their Contribution, including the right to seek patent protection for them. Contributors may perform, lecture, teach, conduct related research and display all or part of their Contribution, in print or electronic format. Authors may reproduce and distribute their Contribution only for non-commercial purposes. For all copies of the Contribution made by Contributors. Contributors must acknowledge ASME as original publisher, the publication title, and an appropriate copyright notice that identifies ASME as the copyright holder.</p>
                <p>PLEASE READ THE TERMS AND CONDITIONS, WHICH ARE FULLY INCORPORATED IN THIS AGREEMENT:<br>
                <a href='http://www.asme.org/kb/proceedings/proceedings/copyright-terms-and-conditions' target='_blank'>http://www.asme.org/kb/proceedings/proceedings/copyright-terms-and-conditions</a></P>
            </div>
            <table  class="table">
            <tr>
                <td>
                    <div class="form-group">
                        <label for="tempName">Name<span class="required">*</span> :</label>
                        <input type="text" name="tempName" placeholder="Enter name" class="form-control" id="tempName" value="{{old('tempName')}}" required>
                        <div class="alert alert-danger" role="alert">{{$errors->first('tempName')}}</div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="tempDate">Date<span class="required">*</span> :</label>
                        <input readonly type="text" name="tempDate" placeholder="Enter name" class="form-control" id="tempDate" value="{{date('Y-m-d')}}" required>
                        <div class="alert alert-danger" role="alert">{{$errors->first('tempDate')}}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <label for="professional_affiliation">Professional Affiliation :</label>
                        <input type="text" name="professional_affiliation" placeholder="Enter professional affiliation" class="form-control" id="professional_affiliation" value="{{old('professional_affiliation')}}">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="asme_affiliation">ASME Affiliation :</label>
                        <input type="text" name="asme_affiliation" placeholder="Enter ASME affiliation" class="form-control" id="asme_affiliation" value="{{old('asme_affiliation')}}">
                    </div>
                </td>
            </tr>
            </table>
            <div class="form-group">
                <button type="submit" name="submitButton" value="acceptCopyright" class="btn btn-primary">I Accept</button>
            </div>
        </form>
    </div>
</div>
@endsection
