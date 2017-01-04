
$(document).ready(function(){
   $('.loader').hide();
   $('#pagepicker').hide();
   $("#weight").slider({tooltip: 'always'});
   $("#height").slider({tooltip: 'always'});
   $("#age").slider({tooltip: 'always'});

   $('#seachbutton').click(function(){
      if(!check())
         return false;

      $(this).hide();
      $('.loader').show();
      $('#container').html('');
      console.log('floor',$('#floor').val())
      var searchData = {
         name : $('#name').val(),
         floor : $('#floor').val(),
         placelivingCountry : $('#placelivingCountry').val(),
         placelivingCity : $('#placelivingCity').val(),
         ageMin : ($('#age').val()).split(',')[0],
         ageMax : ($('#age').val()).split(',')[1],
         familyposition : $('#familyposition').val(),
         children : $('#children').val(),
         smoking : $('#smoking').val(),
         drink : $('#drink').val(),
         heightMin: ($('#height').val()).split(',')[0],
         heightMax: ($('#height').val()).split(',')[1],
         weightMin : ($('#weight').val()).split(',')[0],
         weightMax : ($('#weight').val()).split(',')[1],
         eyes : $('#eyes').val(),
         hair : $('#hair').val(),
         pagenum : parseInt($('#pagepicker').text().trim())
      }

      $.ajax({
         url : "search.php",
         method : "POST",
         data : (searchData),
         success : function(data){
            console.log('predata',data);
            showResults(JSON.parse(data));

            $('.loader').hide();
            $('#seachbutton').show();
         },
         error : function(error){
            console.error('error in searching',error);
            alert('Error in searching')
         }
      })
   })

   function showResults(data){
      console.log('data',data);
      if(data && data.length > 0){
         var pagenum = parseInt($('#pagepicker').text().trim());

         if(data.length > 20 || pagenum > 1){//25
            $('#pagepicker').show();
            $('#pagepicker .left').show();
            $('#pagepicker .right').show();

            if(pagenum == 1)
              $('#pagepicker .left').hide();
            if(data.length < 21)
              $('#pagepicker .right').hide();
         }

         var users = data.map(function(item,i){
            if(i == 21)return;
            var card = "<a class='card' href='"+item.name+"'>"+
               "<div class='cardphoto'>"+
                  "<img class='userphoto' src='"+item.photo+"'/>"+
                "</div>"+
                 "<div class='userinfo'>"+
                     "<div class='rowname'>"+item.name+
                     "</div>"+
                     "<div class='rowinfo'>"+
                        "<span class='glyphicon glyphicon-map-marker' aria-hidden='true'></span>"+item.place+
                     "</div>"+
                     "<div class='rowinfo'>"+
                        "<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span>"+item.age+
                     "</div>"+
                     "<div class='rowinfo'>"+
                        "<span class='glyphicon glyphicon-user' aria-hidden='true'></span>"+item.family+
                     "</div>"+
                 "</div>"+
            "</a>";

            return card;
         })

         $('#container').html(users);
      } else {
         $('#container').html("По данному запросу результатов нет.");
      }
   }

   function check(){
      var result = true;

      // if(($('#name').val()).length == 0){
      //    $('#name').parent().addClass('has-error');
      //    result = false;
      // } else {
      //    $('#name').parent().removeClass('has-error');
      // }

      return result;
   }

   $('#pagepicker .right').click(function(){
      $(this).parent().children('div').text(parseInt($(this).parent().text()) + 1);
      $('#seachbutton').click();
   });

   $('#pagepicker .left').click(function(){
      $(this).parent().children('div').text(parseInt($(this).parent().text()) - 1);
      $('#seachbutton').click();
   })
})
