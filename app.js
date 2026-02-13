
function openDeleteModal(url){
  const back=document.getElementById('modalBack');
  const yes=document.getElementById('modalYes');
  back.style.display='flex';
  yes.setAttribute('href', url);
}
function closeDeleteModal(){
  const back=document.getElementById('modalBack');
  back.style.display='none';
}
