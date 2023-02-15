@extends('layouts.app')
<script src="{{ asset('js/jquery-3.5.1.js') }}"></script>

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


      <div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{__('Registration List')}}</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>

      <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>&nbsp;</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-default" href="{{ route('registrations.create') }}"> Create New Registration</a>
                             
            </div>
        </div>
      </div>

      @include('layouts.flash')

      <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">

          <thead>
            <tr>
              <th>Name</th>
              <th>Reg Type</th>
              <th>mobile</th>              
              <th>City</th>   
              <th>Added By</th>              
              <th width="280px">Action</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
          <tfoot>
            <tr>
              <th>Name</th>
              <th>Reg Type</th>
              <th>mobile</th>              
              <th>City</th>        
              <th>Added By</th>       
              <th width="280px">Action</th>
            </tr>
          </tfoot>
      </table>


		</div>
  </div>
  
  



  {!! Form::open(['method' => 'DELETE','route' => ['registrations.destroy', 1],'id'=>'deleteRow','style'=>'display:inline']) !!}

  {!! Form::close() !!}

	<!-- /#page-wrapper -->
  
  <script>


    var url = "{{url('/')}}";
    var table = '';

    jQuery(document).ready(function() {

          //jQuery('#tableData').append('<caption style="caption-side: bottom">A fictional company\'s staff table.</caption>');

          table = jQuery('#tableData').DataTable({
            'processing': true,
            'serverSide': true,
            'lengthMenu': [
              [10, 25, 50, -1], [10, 25, 50, "All"]
            ],
            dom: 'Bfrtip',
            buttons: [
            {extend:'csvHtml5',
              exportOptions: {
                columns: [1,2,3,4,5,6,7,8]//"thead th:not(.noExport)"
              },
              className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
            },
            {extend: 'pdfHtml5',
              exportOptions: {
                columns: [1,2,3,4,5,6,7,8] //"thead th:not(.noExport)"
              },
              title: 'Site Details',
              className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
              customize : function(doc){
                    var colCount = new Array();
                    var length = $('#reports_show tbody tr:first-child td').length;
                    //console.log('length / number of td in report one record = '+length);
                    $('#reports_show').find('tbody tr:first-child td').each(function(){
                        if($(this).attr('colspan')){
                            for(var i=1;i<=$(this).attr('colspan');$i++){
                                colCount.push('*');
                            }
                        }else{ colCount.push(parseFloat(100 / length)+'%'); }
                    });
                    doc.styles.title = {
                      color: 'black',
                      fontSize: '20',
                      alignment: 'left'
                    }
              }
            },
            {
            extend:'pageLength',
            className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },

            }
            ],
            'sPaginationType': "simple_numbers",
            'searching': true,
            "bSort": true,
            "fnDrawCallback": function (oSettings) {

            },
            'fnRowCallback': function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              //if (aData["status"] == "1") {
                //jQuery('td', nRow).css('background-color', '#6fdc6f');
              //} else if (aData["status"] == "0") {
                //jQuery('td', nRow).css('background-color', '#ff7f7f');
              //}
              //jQuery('.popoverData').popover();
            },
						"initComplete": function(settings, json) {
              //jQuery('.popoverData').popover();
					  },
            'ajax': {
              'url': '{{ url("/") }}/registrations/ajaxData',
              'headers': {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              'type': 'post',
              'data': function(d) {
                //d.statusFilter = jQuery('#statusFilter').val();
                d.parent = jQuery('#parentFilter option:selected').val();
                //d.search = jQuery("#msds-select option:selected").val();
              },
            },

            'columns': [

              {
                  'data': 'name',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    return row.name;
                  }
              },
              {
                  'data': 'alias_name',
                  'className': 'col-md-1',
                  'render': function(data,type,row){

                    return row.regtype_name;
                  }
              },
              {
                  'data': 'project',
                  'className': 'col-md-2',
                  'render': function(data,type,row){

                    return row.mobile;
                  }
              },
              {
                  'data': 'location',
                  'className': 'col-md-2',
                  'render': function(data,type,row){

                    return row.city;
                  }
              },
              {
                  'data': 'location',
                  'className': 'col-md-2',
                  'render': function(data,type,row){

                    return row.added_user;
                  }
              },
              {
                'data': 'Action',
                'orderable': false,
                'className': 'col-md-3',
                'render': function(data, type, row) {
                  var buttonHtml = '<a class="btn btn-primary" href="'+url+'/registrations/'+row.id+'/edit">Edit</a>&nbsp;&nbsp;<a class="btn btn-danger" href="javascript:void(0);" onclick="deleteData('+row.id+')">Delete</a>&nbsp;<a class="btn btn-warning printidcard" href="javascript:void(0)" onclick="openPopUp('+row.id+')">IDCard</a>';
                  return buttonHtml;
                }
              }
            ]
          });


        

  });

  function openPopUp(id){
    var url = "{{url('/')}}/registrations/icard/"+id;
    var NWin = window.open(url, '', 'height=800,width=800');
    if (window.focus) 
    {
      NWin.focus();
    }
    return false;
  }

  function isRadioChecked(){
      if($("input[type='radio'].site_chkbox").is(':checked')) {
          var chkedval = $("input[type='radio'].site_chkbox:checked").val();        
          return chkedval;
      }else{
        return 0;
      }
  }  

  
  function deleteData(id){
    $.confirm({
        title: 'Confirm!',
        content: 'Are you sure want to delete?',
        buttons: {
            confirm: function () {
              $('#deleteRow').attr('action', url+"/registrations/"+id).submit();
            },
            cancel: function () {
                return true;
            }
        }
    });
  }


</script>



@endsection



