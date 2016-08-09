// Start when the page is loaded
$(document).ready(function(){

	// Get base URL (without controller and action)
	var baseURL = window.location.href;
	if(baseURL.substr(-1, 1) == "/"){
		var strLen = baseURL.length;
		baseURL = baseURL.slice(0,strLen-1);
	}
	baseURL = baseURL.split("/");
	baseURL = baseURL.slice(0, -2);
	baseURL = baseURL.join("/")+"/";
	var getParams=parseGetParams();

	// JS Tree actions class
	var JSTree = {

		// Basic variables
		options: {
			all_items_url:	baseURL + "menus/jstreeactions",
			all_items_params:{action: "get_items",menu:getParams['menuId']},
			all_items_request_type:	'post',
			tree_container:	"#jstree",
			add_button: "#add_menu"
		},
	
	
		// This will contain the HTML that we should show for the JS Tree
		items:	"",
	
	
		// Prepare the array - construct the HTML that we need to append in the main container
		prepare_items: 			function(array){
		
									// Workaround for $.each
									var ths = this;
									
									// Output for all the html that we need to build the tree
									var html = "";
									
									// For each menu
									$.each(array, function(k, v){

										// Add menu
										ths.items += "<li id='list_"+v.id+"'>";
										ths.items += "<div class='items "+v.status+"' title='"+v.url+"' login_state_id='"+v.login_state_id+"' data-icon='"+v.icon+"' data-window='" + v.new_window + "'>"+v.text;
										ths.items += "</div>";
										//ths.items += "<input type='hidden' id='posts_"+v.id+"' value='"+v.posts+"'>";
										
										// If the menu has childrens - add them too in the current <li>
										if(v.childs){
											ths.items += ths.prepare_items_childs(v.childs);
										}
										
										// Close the menu <li>
										ths.items += "</li>";
									});
								},

		// Prepare items childs
		prepare_items_childs:	function(array){
		
									// Workaround for $.each
									var ths = this;
									
									// Output for the chield html
									var html = "";
									
									// For each child
									$.each(array, function(k, v){

										// Add child name
										html += "<li id='list_"+v.id+"'>";
										html += "<div class='items "+v.status+"' title='"+v.url+"' login_state_id='"+v.login_state_id+"' data-window='" + v.new_window + "'>"+v.text;
										html += "</div>";
										//html += "<input type='hidden' id='posts_"+v.id+"' value='"+v.posts+"'>";
										
										// If the child has chields
										if(v.childs){
											
											// Add them to the output too
											html += ths.prepare_items_childs(v.childs);
										}
									})
									
									// If we found chields in the current level - return them
									if(html){
										return "<ol>"+html+"</ol>";
										
									// If no chields are found - return empty string
									} else {
										return "";
									}
								},
		
		
		// Load all items from AJAX request using JSON
		get_items:				function(){
									var test = "";
									var ths = this;
									
									$.ajax({
										url: this.options.all_items_url,
										dataType: 'html',
										data: this.options.all_items_params,
										async: false,
										type: this.options.all_items_request_type,
										success: function(data) {
											test = $.parseJSON(data);
											if(test.length == "0"){
												$(ths.options.add_button).show();
											}
										}
									});
									
									this.prepare_items(test);
								},
	
	
		// Initialize tree
		initialize: 			function(){
									this.get_items();
									$(this.options.tree_container).append(this.items);
								}
	}

	// Start tree
	JSTree.initialize();

	// Add effects to the tree
	$('#jstree').nestedSortable({
		disableNesting: 'no-nest',
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		items: 'li',
		maxLevels: 300,
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 25,
		tolerance: 'pointer',
		toleranceElement: '> div'
	});



	
});
function parseGetParams() {
	var $_GET = {};
	var __GET = window.location.search.substring(1).split("&");
	for(var i=0; i<__GET.length; i++) {
		var getVar = __GET[i].split("=");
		$_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1];
	}
	return $_GET;
}