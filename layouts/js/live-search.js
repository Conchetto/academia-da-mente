(function(a){a(document).ready(function(){a(".live-header-search-field, .header-search-field").live("keyup",function(b){b.preventDefault();b=a(".live-header-search-field, .header-search-field").val();var c={action:"king_live_search",keyword:b};3<=b.length&&a.ajax({type:"POST",url:liveSeach.ajaxurl,data:c,beforeSend:function(){a("#king-results").html('<div class="loader"></div>')},success:function(b){a("#king-results").html(b)}})})})})(jQuery);