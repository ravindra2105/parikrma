var site_url = '';
var token = '';
function setSiteURL(url,toekn){
    site_url = url;
    token = token;
}

$(document).ready(function(){
    $("#ajax_site_id").on('change',function(e){

        e.preventDefault();

        var ajax_site_id = $(this).val();
        if(!ajax_site_id){
            jQuery.alert({
                title: 'Alert!',
                content: 'Please select site!',
            });
            return false;
        }

        jQuery.ajax({
            url: site_url+"/divisions/getDivisionBySite",
            method: 'post',
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "site_id": ajax_site_id
            },
            success: function(result){
                
                var html = '<option value="0">Select Division</option>'
                if(result.status){
                    jQuery.each(result.data, function(index, item) {                        
                        html += "<option value='"+item.division_id+"'>"+item.division_name+"</option>";
                    });
                }
                $('#ajax_division_id').empty().append(html);
                console.log(result);
            }});
    });


    $("#ajax_division_id").on('change',function(e){

        e.preventDefault();
        var division_id = $(this).val();
        var sitedata = false;
        if($(this).attr('sitedata') != undefined){
            var sitedata = true;
        }
        
        if(!division_id){
            jQuery.alert({
                title: 'Alert!',
                content: 'Please select division!',
            });
            return false;
        }

        jQuery.ajax({
            url: site_url+"/locations/getLocationByDivision",
            method: 'post',
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "division_id": division_id,"sitedata":sitedata
            },
            success: function(result){
                var html = '<option value="0">Select Location</option>'
                if(result.status){
                    jQuery.each(result.data, function(index, item) {
                        html += "<option value='"+item.id+"'>"+item.name+"</option>";
                    });
                }
                $('#ajax_location_id').empty().append(html);
                console.log(result);
            }});
    });


    $("#ajax_location_id").on('change',function(e){


        e.preventDefault();

        var location_id = $(this).val();
        var sitedata = false;
        if($(this).attr('sitedata') != undefined){
            var sitedata = true;
        }
        


        if(!location_id){
            jQuery.alert({
                title: 'Alert!',
                content: 'Please select location!',
            });
            return false;
        }

        jQuery.ajax({
            url: site_url+"/feeders/getFeederByLocation",
            method: 'post',
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "location_id": location_id,"sitedata":sitedata
            },
            success: function(result){
                var html = '<option value="0">Select Feeders</option>'
                if(result.status){
                    jQuery.each(result.data, function(index, item) {
                        html += "<option value='"+item.id+"'>"+item.name+"</option>";
                    });
                }
                $('#ajax_feeder_id').empty().append(html);
                console.log(result);
            }});
    });



   

    $("#html_country_id").on('change',function(e){

        e.preventDefault();
        var data_country_id = $(this).val();

        if(!data_country_id){
            jQuery.alert({
                title: 'Alert!',
                content: 'Please select Country!',
            });
            return false;
        }

        jQuery.ajax({
            url: site_url+"/getStatesByCountry",
            method: 'post',
            data: {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "id": data_country_id
            },
            success: function(result){
                var html = '<option value="0">Select State</option>'
                if(result.status){
                    jQuery.each(result.data, function(index, item) {
                        html += "<option value='"+item.id+"'>"+item.name+"</option>";
                    });
                }
                $('#html_state_id').empty().append(html);
                console.log(result);
            }});
    });

  


    $('#multiselect').multiselect();

       

});



