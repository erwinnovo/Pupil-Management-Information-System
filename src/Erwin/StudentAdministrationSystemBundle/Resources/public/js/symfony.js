function confirmDeletePupil()
{
    var con_add = confirm("Do you want to delete the current pupil record? (Warning: This will permanently erase the record from the database!)");
    
    if (con_add)
    {
        document.forms["form"].submit();
    }
}


function search()
{   
    document.forms["form"].submit(); 
}


