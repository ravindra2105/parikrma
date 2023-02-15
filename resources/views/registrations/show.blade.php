@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Show Registration')}}</h1>
                    </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <a class="btn btn-primary pull-right" href="{{ route('registrations.index') }}"> Back</a>
            </div>
            
            <br/><br/><br/><br/>
            @include('layouts.flash')
            <div id="content">
                <div class="row">
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $registration->name }}
                        </div>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>Registration Type:</strong>
                            {{ $registration->reg_type->name }}
                        </div>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>Address line1:</strong>
                            {{ $registration->address }}
                        </div>
                    </div>
                   
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>Country:</strong>
                            {{ $site->country->name }}
                        </div>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>State:</strong>
                            {{ $registration->state->name }}
                        </div>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>City:</strong>
                            {{ $registration->city }}
                        </div>
                    </div>
                    

                </div>


                </div> <!--content close-->

		</div>
	</div>
	<!-- /#page-wrapper -->
<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<script>

var doc = new jsPDF();
var specialElementHandlers = {
    '#editor': function (element, renderer) {
        return true;
    }
};

$('#cmd').click(function () {
    doc.fromHTML($('#content').html(), 15, 15, {
        'width': 170,
            'elementHandlers': specialElementHandlers
    });
    doc.save('site-detail.pdf');
});


</script>

@endsection
