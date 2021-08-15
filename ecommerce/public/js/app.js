// // close button for modals
// document.querySelectorAll('.btn-close').forEach(
//     $element => $element.addEventListener('click', alert(1))
// );

// function removeHTML(){
//     document.querySelectorAll('.modal').forEach(
//         $element => $element.remove()
//     );
// }


// select all checkboxes when the main one is select
const selectAll = document.getElementById('select-all');

    if(selectAll){
        selectAll.onclick = function() {
            var checkboxes = document.getElementsByName('item');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    }
