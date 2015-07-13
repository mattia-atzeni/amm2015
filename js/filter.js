$(document).ready( function() {
    $('#filter').click( function(event) {
    	$("#courses-list").empty();
        event.preventDefault();
        var _name = $('#name').val();
        var _categories = Array();
        $('#categories input[name=categories]:checked').each( function() {
            _categories.push($(this).val());
        });
        
        var _hosts = Array();
        $('#hosts input[name=hosts]:checked').each( function() {
            _hosts.push($(this).val());
        });
        
        var arg = {
            name: _name,
            categories: _categories,
            hosts: _hosts,
            cmd: 'filter'
        };
        
        $.ajax({
           url: 'learner/filter',
           data: arg,
           dataType: 'json',
           success: function(data, state) {
               for (var key in data['courses']) {
                   var course = data['courses'][key];
                   $("#courses-list").append(
                        "<li class=\""+ course['host_name'] + "\">\n\
                            <a class=\"course\" href=\"" + course['link'] + "\" target=\"_blank\">\n\
                                <h4 class=\"name\">" + course['name'] + "</h4>\n\
                                <p class=\"category\">Categoria: " + course['category'] + "</p>\n\
                            </a>\n\
                            <form action=\"learner\" method=\"post\">\n\
                                <input type=\"hidden\" name=\"cmd\" value=\"join\">\n\
                                <input type=\"hidden\" name=\"course_id\" value=\"" + course['id'] + "\">\n\
                                <button class=\"course-button\" type=\"submit\">Iscriviti</button>\n\
                            </form>\n\
                        </li>");
               }
               
           },
           error: function(data, state) {
       
           }
           
        });
        
    });
});
