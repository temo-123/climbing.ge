var fixHelperModified = function(e, tr) {
	var $originals = tr.children();
	var $helper = tr.clone();
	$helper.children().each(function(index) {
		$(this).width($originals.eq(index).width())
	});
	return $helper;
},
updateIndex = function(e, ui) {
	$('td.index', ui.item.parent()).each(function (i) {
		$(this).html(i + 1);
	});
	$('input[type=text]', ui.item.parent()).each(function (i) {
		$(this).val(i + 1);
	});
};

$("#myTable tbody").sortable({
	helper: fixHelperModified,
	stop: updateIndex
}).disableSelection();

$("tbody").sortable({
	distance: 5,
	delay: 100,
	opacity: 0.6,
	cursor: 'move',
	update: function() {}
});