@extends('layouts.front.app')

@section('content')

	<!-- Navigation -->

	<div id="page-wrapper">
		<div class="container-fluid">

            
            {!! Form::open(array('route' => 'registrations.store','method'=>'POST','enctype'=>'multipart/form-data')) !!}
            <div class="row">
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Registration Type:</strong>
                        {!! Form::select('reg_type_id', $reg_types,null, array('class' => 'form-control','placeholder'=>'Select Project')) !!}
                    </div>
                </div>
                                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Age:</strong>
                        {!! Form::text('age', null, array('placeholder' => 'Age','class' => 'form-control')) !!}
                    </div>
                </div>   

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Mobile:</strong>
                        {!! Form::text('mobile', null, array('placeholder' => 'Mobile','class' => 'form-control')) !!}
                    </div>
                </div>  

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email:</strong>
                        {!! Form::text('email', null, array('placeholder' => 'email','class' => 'form-control')) !!}
                    </div>
                </div> 

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Qualification:</strong>
                        {!! Form::text('qualification', null, array('placeholder' => 'Qualification','class' => 'form-control')) !!}
                    </div>
                </div> 

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Profession:</strong>
                        {!! Form::text('profession', null, array('placeholder' => 'Profession','class' => 'form-control')) !!}
                    </div>
                </div>  

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Diksha Guru:</strong>
                        {!! Form::text('diksha_guru', null, array('placeholder' => 'Diksha Guru','class' => 'form-control')) !!}
                    </div>
                </div>                                    
                              
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Address:</strong>
                        {!! Form::textarea('address', null, array('placeholder' => 'Address','rows'=>2,'class' => 'form-control')) !!}
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Country:</strong>
                        {!! Form::select('country_id', $country,[], array('placeholder'=>'Select country','class' => 'form-control','id'=>'html_country_id')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>State:</strong>
                        {!! Form::select('state_id', [],[], array('placeholder'=>'Select state','class' => 'form-control','id'=>'html_state_id')) !!}
                    </div>
                </div>
                

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>City:</strong>
                        {!! Form::text('city', null, array('placeholder' => 'city','class' => 'form-control')) !!}
                    </div>
                </div>

               

                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <a class="btn btn-primary" href="{{ route('registrations.index') }}"> Back</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
                        


		</div>		
	</div>
    <!-- /#page-wrapper -->
    <style>
    .fltleft{
        
    }
    </style>
    <script>
        $(document).ready(function() {
            
        });
    </script>
            
@endsection
