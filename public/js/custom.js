function deleteData(x)
{
    var delbtn = $(x);
    
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover element',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.value) 
        {
          var td = delbtn.parent();    //get the parent of the button
          var form = td.children().last(); //get the form child element
          form.submit();                    //submit the form
          
        } else if (result.dismiss === Swal.DismissReason.cancel) 
        {
           
        }
      })
}
