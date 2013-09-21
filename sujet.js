function zone_file(click)
{
	id = $(click).attr('id');
	if($('#file_'+id).css('display') === 'none')
	{
		$('#file_'+id).css('display', 'inline-block');
	}
	else if($('#file_'+id).css('display') === 'inline-block')
	{
		$('#file_'+id).css('display', 'none');
	}
}
function creasuj()
{
	if($('.creasuj').css('display') === 'none')
	{
		$('.crea_suj').attr('value', 'Masquer');
		$('.creasuj').fadeIn();
	}
	else if($('.creasuj').css('display') === 'block')
	{
		$('.crea_suj').attr('value', 'Créer un sujet');
		$('.creasuj').fadeOut();
	}
}
function creamess()
{
	if($('.creamess').css('display') === 'none')
	{
		$('.crea_mess').attr('value', 'Masquer');
		$('.creamess').fadeIn();
	}
	else if($('.creamess').css('display') === 'block')
	{
		$('.crea_mess').attr('value', 'Répondre');
		$('.creamess').fadeOut();
	}
}