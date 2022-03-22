window.addEventListener("resize", function() {
		"use strict"; window.location.reload(); 
	});


	document.addEventListener("DOMContentLoaded", function(){

		// make it as accordion for smaller screens
		if (window.innerWidth > 992) {

			document.querySelectorAll('.navbar .nav-item').forEach(function(everyitem){
				
				everyitem.addEventListener('mouseover', function(e){

					let el_link = this.querySelector('a[data-bs-toggle]');

					if(el_link != null){
						let nextEl = el_link.nextElementSibling;
						el_link.classList.add('show');
				 		nextEl.classList.add('show');
					}
					
				});
				everyitem.addEventListener('mouseleave', function(e){
				 	let el_link = this.querySelector('a[data-bs-toggle]');
					
					if(el_link != null){
						let nextEl = el_link.nextElementSibling;
						el_link.classList.remove('show');
				 		nextEl.classList.remove('show');
					}
					

				})
			});

		}
		// end if innerWidth
	}); 
/* globals Chart:false, feather:false */
function changeBackground(obj) {
    $(obj).addClass("bg-success");
    //$(obj).addClass("bg-warning");
}

function saveData(obj, nilai_id, column) {
    var customer = {
        nilai_id: nilai_id,
        column: column,
        value: obj.innerHTML
    }
    $.ajax({
        type: "POST",
        url: "modul/akademik/nilai_update.php",
        data: customer,
        dataType: 'json',
        success: function(data){
          console.log(data); 
          $(obj).addClass('bg-success').delay(1000).queue(function(next){
            $(this).removeClass('bg-danger');
            next();
          });         
          //$(obj).addClass("bg-success");
          //$(obj).removeClass("bg-danger");
        }
   });
}
/*
function highlightEdit(editableObj) {
	$(editableObj).css("background","#dc3545");
} 

function saveInlineEdit(editableObj,column,nilai_id) {
	// no change change made then return false
	if($(editableObj).attr('data-old_value') === editableObj.innerHTML)
	return false;
	// send ajax to update value
	$(editableObj).css("background","#dc3545 url(loader.gif) no-repeat right");
	$.ajax({
		url: "modul/akademik/nilai_update.php",
		cache: false,
		data:'column='+column+'&value='+editableObj.innerHTML+'&nilai_id='+nilai_id,
		success: function(response)  {
			console.log(response);
			// set updated value as old value
			$(editableObj).attr('data-old_value',editableObj.innerHTML);
			$(editableObj).css("background","#198754");			
		}          
   });
}
*/
(function () {
  "use strict";

  feather.replace({ "aria-hidden": "true" });
})();
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".sidebar .nav-link").forEach(function (element) {
    element.addEventListener("click", function (e) {
      let nextEl = element.nextElementSibling;
      let parentEl = element.parentElement;

      if (nextEl) {
        e.preventDefault();
        let mycollapse = new bootstrap.Collapse(nextEl);

        if (nextEl.classList.contains("show")) {
          mycollapse.hide();
        } else {
          mycollapse.show();
          // find other submenus with class=show
          var opened_submenu =
            parentEl.parentElement.querySelector(".submenu.show");
          // if it exists, then close all of them
          if (opened_submenu) {
            new bootstrap.Collapse(opened_submenu);
          }
        }
      }
    });
  });
});

(function ($bs) {
  const CLASS_NAME = "has-child-dropdown-show";
  $bs.Dropdown.prototype.toggle = (function (_orginal) {
    return function () {
      document.querySelectorAll("." + CLASS_NAME).forEach(function (e) {
        e.classList.remove(CLASS_NAME);
      });
      let dd = this._element
        .closest(".dropdown")
        .parentNode.closest(".dropdown");
      for (; dd && dd !== document; dd = dd.parentNode.closest(".dropdown")) {
        dd.classList.add(CLASS_NAME);
      }
      return _orginal.call(this);
    };
  })($bs.Dropdown.prototype.toggle);

  document.querySelectorAll(".dropdown").forEach(function (dd) {
    dd.addEventListener("hide.bs.dropdown", function (e) {
      if (this.classList.contains(CLASS_NAME)) {
        this.classList.remove(CLASS_NAME);
        e.preventDefault();
      }
      e.stopPropagation(); // do not need pop in multi level mode
    });
  });

  // for hover
  function getDropdown(element) {
    return $bs.Dropdown.getInstance(element) || new $bs.Dropdown(element);
  }

  document
    .querySelectorAll(".dropdown-hover, .dropdown-hover-all .dropdown")
    .forEach(function (dd) {
      dd.addEventListener("mouseenter", function (e) {
        let toggle = e.target.querySelector(
          ':scope>[data-bs-toggle="dropdown"]'
        );
        if (!toggle.classList.contains("show")) {
          getDropdown(toggle).toggle();
        }
      });
      dd.addEventListener("mouseleave", function (e) {
        let toggle = e.target.querySelector(
          ':scope>[data-bs-toggle="dropdown"]'
        );
        if (toggle.classList.contains("show")) {
          getDropdown(toggle).toggle();
        }
      });
    });
})(bootstrap);

$('#password, #confirm_password').on('keyup', function () {
  if ($('#password').val() == $('#confirm_password').val()) {
    $('#message').html('Matching').css('color', 'green');
  } else 
    $('#message').html('Not Matching').css('color', 'red');
});

function checkPass()
{
    var pass1 = document.getElementById('pass1');
    var pass2 = document.getElementById('pass2');
    var message = document.getElementById('error-nwl');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
 	
    if(pass1.value.length > 7)
    {
        pass1.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = "Jumlah karakter disetujui!"
    }
    else
    {
        pass1.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = " Password minimal 8 karakter!"
        return;
    }
  
    if(pass1.value == pass2.value)
    {
        pass2.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = "OK!"
    }
  else
    {
        pass2.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = " Password tidak sama"
    }
}  

function activaTab(tab){
    $('.nav-pills a[href="#' + tab + '"]').tab('show');
};

$('#selectedFile').change(function () {
    var a = $('#selectedFile').val().toString().split('\\');
    $('#fakeInput').val(a[a.length -1]);
});