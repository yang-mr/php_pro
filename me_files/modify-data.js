$(document).ready(function(){
	function starLeve(Dom){
		var num;
		$(Dom).click(function(){
			var num = parseInt($(this).attr("data-val"));
			$(this).parent().find("p").removeClass("d_dark");
			for(i=0; i <= num;i++){
				$(this).parent().find("p").eq(i).addClass("d_dark");
			}
		});
		var num3;
		$(Dom).mouseover(function() {
			var num = parseInt($(this).attr("data-val"));
			$(this).parent().find("p").addClass("l_blue");
			$(this).parent().find("p").removeClass("d_hover");
			for(i=0; i <= num;i++){
				$(this).parent().find("p").eq(i).addClass("d_hover");
			}
			

		});
		$(Dom).mouseout(function() {
			var num3 = $(this).parent().find(".d_dark").length;
			$(this).parent().find("p").removeClass("l_blue");
			$(this).parent().find("p").removeClass("d_hover");
			for(i=0; i < num3;i++){
				$(this).parent().find("p").eq(i).addClass("d_dark");
			}
		});
		
	}
	starLeve(".man_bar p");
	starLeve(".woman_bar p");
});