$(function () {

	$(document).ready(function() {

	try {
			oHandler = $(".showDataBase").msDropDown().data("dd");
			//oHandler.visible(true);
			//alert($.msDropDown.version);
			//$.msDropDown.create("body select");
			$("#ver").html($.msDropDown.version);
			} catch(e) {
				alert("Error: "+e.message);
			}
	})

	// jQuery Tipsy
	$('[rel=tooltip], #main-nav span, .loader').tipsy({gravity:'s', fade:true}); // Tooltip Gravity Orientation: n | w | e | s

	// IE7 doesn't support :disabled
	$('.ie7').find(':disabled').addClass('disabled');

	// Menu Dropdown
	$('#main-nav li ul').hide(); //Hide all sub menus
	$('#main-nav li.current a').parent().find('ul').slideToggle('slow'); // Slide down the current sub menu
	$('#main-nav li a').click(
		function () {
			$(this).parent().siblings().find('ul').slideUp('normal'); // Slide up all menus except the one clicked
			$(this).parent().find('ul').slideToggle('normal'); // Slide down the clicked sub menu
			return false;
		}
	);
	
	$('#main-nav li a.no-submenu, #main-nav li li a').click(
		function () {
			window.location.href=(this.href); // Open link instead of a sub menu
			return false;
		}
	);
	
	//listing table js
	$('.check-all').click(
		function(){
			$(this).parents('form').find('input:checkbox').attr('checked', $(this).is(':checked'));
			if ($(this).is(':checked')) {
				$(this).parents('form').find('input:checkbox').parents('tbody').find('tr').addClass("selected");
			} else {
				$(this).parents('form').find('input:checkbox').parents('tbody').find('tr').removeClass("selected");
			}
			
		}
	)
	
	$('.td-chkbox .checkbox').click (
		function() {
			if ($(this).is(':checked')) {
				$(this).parents('tr').addClass("selected");
			} else {
				$(this).parents('tr').removeClass("selected");
			}
			
			var isAllChecked=true;
			
			var $p = $(this).parents('tbody').find('tr');
			for (var index=0; index<$p.length; index++){
				if (!$p.eq(index).find('input:checkbox').is(':checked')) {
					isAllChecked=false;
				}
			}
			$('.check-all').attr('checked',isAllChecked);
		}
	);

	// Notification Close Button
	$('.close-notification').click(
		function () {
			$(this).parent().fadeTo(350, 0, function () {$(this).slideUp(600);});
			return false;
		}
	);
	
	// set function for dropdown db select
	$('.showDataBase').change(
		function () {
			if ($(this).val()=="showALL") {
				//redirect to database listing page
				window.location.href="databases.php";
			} else {
				//redirect to database tables listing page
				window.location.href="dbtables.php?id="+this.val();
			}
		}
	);
	
	
});