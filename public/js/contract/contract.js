/**
 * Created by dewey on 2017/7/26.
 */
$(function(){
    //转班
    $('#returnClassBtn').on('click', function(){
        if (confirm('确认已选项')) {
            var class_id = $('#returnClassList').val();
            if(class_id==0){
                alert('请选择班级');
                return;
            }
            var conIds = $('#sample-table-2').find('input:checked[value!=0][data-name="check_id"]');
            if (conIds.length == 0) {
                alert('请选择要转班的学员合同');
                return;
            }
            var id_arr = new Array();
            conIds.each(function(){
                id_arr.push($(this).val());
            });
            $.ajax({
                url : '/Admin/contract/returnClass',
                data : {'class_id' : class_id, 'ids': id_arr.join(',')},
                type : 'POST',
                dataType : 'json',
                success : function(e){
                    alert(e.msg);
                    if(e.code == 1) location.reload();
                }
            });
        }
    });
});
