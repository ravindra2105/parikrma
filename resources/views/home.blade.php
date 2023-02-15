@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')
<style>
.panel-blue{
	border-color: #999;
}

.panel-blue >.panel-heading {
    color: #fff;
    background-color: #999;
    border-color: #999;
}
.panel-blue a {
    color: #999;
}

</style>
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Dashboard</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<!--<div class="row">-->

                
   <!--             <div class="col-lg-6 col-md-6">-->
   <!--                 <label for="">Sites</label>-->
   <!--                 <select name="sites" id="sites" class="form-control">-->
   <!--                     <option value="All">All</option>-->
   <!--                     @if(!$sites->isEmpty())-->
   <!--                         @foreach($sites as $value)-->
   <!--                         <option value="{{ $value->id }}">{{ $value->name }}</option>-->
   <!--                         @endforeach-->
   <!--                     @endif-->
   <!--                 </select>-->
   <!--             </div>-->
   <!--             <div class="col-lg-2 col-md-6">-->
   <!--                 <label for="">From Date</label>-->
   <!--                 <input type="date" id="fromDate" class="form-control">-->
   <!--             </div>-->
   <!--             <div class="col-lg-2 col-md-6">-->
   <!--                 <label for="">To Date</label>-->
   <!--                 <input type="date" id="toDate" class="form-control">-->
   <!--             </div>-->
   <!--             <div class="col-lg-2 col-md-6">-->
   <!--                 <label for=""></label>-->
   <!--                 <button class="btn btn" onclick="getAllData()">Search</button>-->
   <!--             </div>-->
   <!--         </div>-->
            <br>
            <div class="row">
                
        				<div class="col-lg-3 col-md-6">
        					<div class="panel panel-primary">
        						<div class="panel-heading dashboard-folder">
        							<div class="row">
        								<div class="col-xs-12" onclick="siteview({{$value->id}})" style="cursor:pointer">
        									<img src="{{url('/')}}/images/folder.png">
        									<div>Total Registration 4</div>
        								</div>
        							</div>
        						</div>
        					</div>
        				</div>

                        <div class="col-lg-3 col-md-6">
        					<div class="panel panel-primary">
        						<div class="panel-heading dashboard-folder">
        							<div class="row">
        								<div class="col-xs-12" onclick="siteview({{$value->id}})" style="cursor:pointer">
        									<img src="{{url('/')}}/images/folder.png">
        									<div>Total Users 4</div>
        								</div>
        							</div>
        						</div>
        					</div>
        				</div>
    				
			</div>
			<!-- /.row -->
			
		</div>
		<!-- /.container-fluid -->
	</div>
    
      
      
<script>
    
</script>      
      
	<!-- /#page-wrapper -->

@endsection
