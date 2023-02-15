@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


      <div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{__('Notification List')}}</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>  

      <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>&nbsp;</h2>
            </div>
            <div class="pull-right">
                &nbsp;
            </div>
        </div>
      </div>

      @include('layouts.flash')
      
      
      <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
          
          <thead>
              <tr>
                  <th>Type</th>                                                                              
                  <th>Subject</th>                                        
                  <th>Email Message</th> 
                  <th>Mobile Message</th> 
                  <th>Notification Message</th> 
                  <th>Action</th> 
              </tr>
          </thead>
          <tbody>
                        
          </tbody>
          <tfoot>
              <tr>                                                        
                  <th>Type</th>                                                                                                  
                  <th>Subject</th>                                                          
                  <th>Email Message</th> 
                  <th>Mobile Message</th> 
                  <th>Notification Message</th> 
                  <th>Action</th> 
              </tr>
          </tfoot>
      </table>  


		</div>		
	</div>
	<!-- /#page-wrapper -->
  {!! Form::open(['method' => 'DELETE','route' => ['notifications.destroy', 1],'id'=>'deleteNotification','style'=>'display:inline']) !!}
      
  {!! Form::close() !!}

  <script>
        
    var url = "{{url('/')}}";
    var table = '';

    jQuery(document).ready(function() {
          
					
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
                columns: [0, 1]//"thead th:not(.noExport)"
              },
              className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
            },
            {extend: 'pdfHtml5',
              exportOptions: {
                columns: [0, 1] //"thead th:not(.noExport)"
              },
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
            "bSort": false,
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
              'url': '{{ url("/") }}/notifications/ajaxData',
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
                  'data': 'itemcode',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.itemcode;
                  }
              },            
              {
                'data': 'subject',
                  'className': 'col-md-3',
                  'render': function(data,type,row){
                    
                    return row.subject;
                }
              },            
              {
                'data': 'message',
                  'className': 'col-md-2',
                  'render': function(data,type,row){                    
                    return row.message;
                  }
              },            
              {
                'data': 'mobile_message',
                  'className': 'col-md-2',
                  'render': function(data,type,row){                    
                    return row.mobile_message;
                  }
              },            
              {
                'data': 'notification_message',
                  'className': 'col-md-2',
                  'render': function(data,type,row){                    
                    return row.notification_message;
                  }
              },            
              {
                'data': 'action',
                  'className': 'col-md-1',
                  'render': function(data,type,row){                    
                    var buttonHtml = '<a class="btn btn-primary" href="'+url+'/notifications/'+row.id+'/edit">Edit</a>';
                    return buttonHtml;
                  }
              }
            ]
          });   


        
  });



function deleteData(id){

  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete?',
      buttons: {
          confirm: function () {
            $('#deleteNotification').attr('action', url+"/notifications/").submit();  
          },
          cancel: function () {
              return true;
          }
      }
  });

}        

 
</script>


@endsection
