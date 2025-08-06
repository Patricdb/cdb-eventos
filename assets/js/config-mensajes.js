jQuery(document).ready(function($){
    if (typeof $.fn.wpColorPicker !== 'undefined') {
        $('.cdb-color-field').wpColorPicker();
    }
    $('#cdb-eventos-add-tipo').on('click', function(e){
        e.preventDefault();
        var table = $('#cdb-eventos-tipos').find('tbody');
        var index = table.find('tr').length;
        var row = '<tr>'+
            '<td><input type="text" name="tipos[slug][]" value="" /></td>'+
            '<td><input type="text" name="tipos[nombre][]" value="" /></td>'+
            '<td><input type="text" name="tipos[clase][]" value="" /></td>'+
            '<td><input type="text" class="cdb-color-field" name="tipos[color][]" value="" /></td>'+
            '<td><input type="text" class="cdb-color-field" name="tipos[color_texto][]" value="" /></td>'+
            '</tr>';
        table.append(row);
        if (typeof $.fn.wpColorPicker !== 'undefined') {
            table.find('.cdb-color-field').wpColorPicker();
        }
    });
});
