var count = 0;
$("#additional_fields").click(function () {
    var html = '';
    html += '<div id="inputFormRow">';
    html += '<div class="input-group mb-3">';
    html += '<input type="text" name="label['+count+'][]" class="form-control m-input" placeholder="Label" autocomplete="off">';
    html += '<input type="text" name="value['+count+'][]" class="form-control m-input" placeholder="Value" autocomplete="off">';
    html += '<div class="input-group-append">';
    html += '<button id="removeRow" type="button" class="btn btn-danger">x</button>';
    html += '</div>';
    html += '</div>';
    
    $('#new').append(html);
});
$("#variationsbtn").click(function (e) {
    e.preventDefault();
    var html = '';
    html += '<div id="inputFormRow">';
    html += '<div class="input-group mb-3">';
    html += '<input type="text" name="key['+count+'][]" class="form-control m-input" placeholder="Label" autocomplete="off">';
    html += '<input type="text" name="val['+count+'][]" class="form-control m-input" placeholder="Value" autocomplete="off">';
    html += '<div class="input-group-append">';
    html += '<button id="removeRow" type="button" class="btn btn-danger">x</button>';
    html += '</div>';
    html += '</div>';
    
    $('#variations').append(html);
});


$(document).on("click", "button#removeRow", function(e){
    e.preventDefault();
    $(this).parent().parent().remove();
});

// $('#variations').hide();
// $("#variationsbtn").click(function () {
//     $('#variations').toggle();
// });

$('#popup').hide();

$(document).on("click", "input#show_popup", function(){
    $('#popup').toggle();
});

$("#close").click(function(){
    // e.preventDefault();
    $("#popup").hide();
});

function search() {
    var input, filter, table, tr, td1, td2, i, txtValue1, txtValue2;
    input = document.getElementById("searchValue");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td1 = tr[i].getElementsByTagName("td")[0];
      td2 = tr[i].getElementsByTagName("td")[1];
      if (td1 || td2) {
        txtValue1 = td1.textContent || td1.innerText;
        txtValue2 = td2.textContent || td2.innerText;
        if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }       
    }
  }

  $(document).ready(function(){
      $(document).on('click', '#show_popup',function(){         
     $.ajax(
         {
         'url': '/index/oneproduct',
         'method':'POST',
         'data' : {'product_id':$(this).data('id')},
         'datatype' : 'JSON'
     }).done(function(data){     
         jData = JSON.parse(data);      
         oneProduct(jData);          
     });
 }); 
});

function oneProduct(data)
{
    
    additionals = data[0];
    variations = data[1];
        
    if (typeof(additionals) != 'undefined') {
        label = additionals['label'];
        value = additionals['value'];
    }
   
    
    if( variations) {        
        variation_field = variations['Variation Field'];
        variation_name = variations['Variation Name'];
        variation_price = variations['Variation Price'];       
    }   
  
    
    var html = '';
    html += '<ul>';
    html += '<li> Product Name : '+data['Name']+' </li>';
    html += '<li> Product Catergory : '+data['Category']+' </li>';
    html += '<li> Product Price : '+data['Price']+' </li>';
    html += '<li> Product Stocks : '+data['Stock']+' </li>';
    html += '<ul>';
    if (typeof(label) != 'undefined' && typeof(value) != 'undefined') {
        html += '<h4>Additional Information</h4>'
        for (var i=0;i<label.length;i++) {
            html += label[i] + " : " + value[i]+"<br>";
        }
    }

    if(typeof(variation_field) != 'undefined') {
        for (key in variation_field) {
            if (variation_field.hasOwnProperty(key))
                count++;
        }
    }

   size = count; 

    if (typeof(variation_field) != 'undefined' && typeof(variation_name) != 'undefined'  && typeof(variation_price) != 'undefined') {
        html += '<h4>Variations</h4>'
        for (var i=1;i<=count;i++) {     
                for (var j= 0;j<variation_field[i].length ; j++) {
                  html += variation_field[i][j]+ " : "+ variation_name[i][j] + " : " + variation_field[i][j+1] + " : " + variation_name[i][j+1] + " : " + variation_price[i] + "<br>";
                  break;
            }         
        }
    }
    $('#data').html(html);
}


