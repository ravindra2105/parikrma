@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Edit Registration')}}</h1>
                    </div>
            </div>

            @include('layouts.flash')

            {!! Form::model($registration, ['method' => 'PATCH','enctype'=>'multipart/form-data','route' => ['registrations.update', $registration->id]]) !!}
            <div class="row">

            <div class="col-xs-2 col-sm-2 col-md-2">
                    <div class="form-group">
                        <button type="button" class="btn btn-success" id="start-camera">Start Camera</button>
                        <button type="button" class="btn btn-danger" id="click-photo">Click Photo</button>
                    </div>
                </div>
                <div class="col-xs-5 col-sm-5 col-md-5">
                    <div class="form-group">
                    <video id="video" width="320" height="240" autoplay></video>
                    </div>
                </div>
                <div class="col-xs-5 col-sm-5 col-md-5">
                    <div id="dataurl-container">
                        <canvas id="canvas" width="320" height="240"></canvas>
                        <textarea id="dataurl" name="img_url" readonly></textarea>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Old Image:</strong>
                        <img src="{{$registration->img_url}}" alt="Photo" title="Registration Pic">
                    </div>
                </div>     
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Registration Type:</strong>
                        {!! Form::select('reg_type_id', $reg_types,null, array('class' => 'form-control','placeholder'=>'Select Project')) !!}
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Subscription Type:</strong>
                        {!! Form::select('subscription_id', $subscriptions,null, array('class' => 'form-control','placeholder'=>'Select Suscription')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Payment Type:</strong>
                        {!! Form::select('payment_type', ['manual'=>'Manual','gateway'=>'Gateway'],null, array('class' => 'form-control','placeholder'=>'Payment Type')) !!}
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
                        {!! Form::select('country_id', $country,$registration->country_id, array('placeholder'=>'Select country','class' => 'form-control','id'=>'html_country_id')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>State:</strong>
                        {!! Form::select('state_id', $state,$registration->state_id, array('placeholder'=>'Select state','class' => 'form-control','id'=>'html_state_id')) !!}
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
    
    <style type="text/css">



#start-camera {
    margin-top: 50px;
}

#video {
    display: none;
    margin: 50px auto 0 auto;
}

#click-photo {
    display: none;
}

#dataurl-container {
    display: none;
}

#canvas {
    display: block;
    margin: 0 auto 20px auto;
}

#dataurl-header {
    text-align: center;
    font-size: 15px;
}

#dataurl {
    display: block;
    height: 100px;
    width: 320px;
    margin: 10px auto;
    resize: none;
    outline: none;
    border: 1px solid #111111;
    padding: 5px;
    font-size: 13px;
    box-sizing: border-box;
}

</style>
    <script>
         let camera_button = document.querySelector("#start-camera");
        let video = document.querySelector("#video");
        let click_button = document.querySelector("#click-photo");
        let canvas = document.querySelector("#canvas");
        let dataurl = document.querySelector("#dataurl");
        let dataurl_container = document.querySelector("#dataurl-container");

        camera_button.addEventListener('click', async function() {
            let stream = null;

            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            }
            catch(error) {
                alert(error.message);
                return;
            }

            video.srcObject = stream;

            video.style.display = 'block';
            camera_button.style.display = 'none';
            click_button.style.display = 'block';
        });

        click_button.addEventListener('click', function() {
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            let image_data_url = canvas.toDataURL('image/jpeg');
            
            dataurl.value = image_data_url;
            dataurl_container.style.display = 'block';
        });
    </script>

@endsection
