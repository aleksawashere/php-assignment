$('#dodajForm').submit(function(){
    event.preventDefault();
    console.log("Dodavanje");
    const $form =$(this);
    const $input = $form.find('input, select, button, textarea');

    const serijalizacija = $form.serialize();
    console.log(serijalizacija);

    $input.prop('disabled', true);

    req = $.ajax({
        url: 'handler/add.php',
        type:'post',
        data: serijalizacija
    });

    req.done(function(res, textStatus, jqXHR){
        if(res=="Success"){
            alert("Kolokvijum uspešno zakazan");
            console.log("Dodar kolokvijum");
            location.reload(true);
        }else console.log("Kolokvijum nije dodat "+res);
        console.log(res);
    });

    req.fail(function(jqXHR, textStatus, errorThrown){
        console.error('Sledeca greska se desila> '+textStatus, errorThrown)
    });
});

$('#btn-obrisi').click(function(){
    console.log("Brisanje");

    const checked = $('input[name=checked-donut]:checked');

    req = $.ajax({
        url: 'handler/delete.php',
        type:'post',
        data: {'id':checked.val()}
    });

    req.done(function(res, textStatus, jqXHR){
        if(res=="Success"){
           checked.closest('tr').remove();
           alert('Obrisan kolokvijum');
           console.log('Obrisan');
        }else {
        console.log("Kolokvijum nije obrisan "+res);
        alert("Kolokvijum nije obrisan ");

        }
        console.log(res);
    });

});

// dugme koje je na glavnoj formi i otvara dijalog za izmenu
$('#btn-izmeni').click(function () {
    const checked = $('input[name=checked-donut]:checked');
    //pristupa informacijama te konkretne forme i popunjava dijalog
    request = $.ajax({
        url: 'handler/get.php',
        type: 'post',
        data: {'id': checked.val()},
        dataType: 'json'
    });


    request.done(function (response, textStatus, jqXHR) {
        console.log('Popunjena');
        $('#predmet').val(response[0]['predmet']);
        console.log(response[0]['predmet']);

        $('#katedra').val(response[0]['katedra'].trim());
        console.log(response[0]['katedra'].trim());

        $('#sala').val(response[0]['sala'].trim());
        console.log(response[0]['sala'].trim());

        $('#datum').val(response[0]['datum'].trim());
        console.log(response[0]['datum'].trim());

        $('#id').val(checked.val());

        console.log(response);
    });

   request.fail(function (jqXHR, textStatus, errorThrown) {
       console.error('The following error occurred: ' + textStatus, errorThrown);
   });

});

//dugme za slanje UPDATE zahteva nakon popunjene forme
$('#izmeniForm').submit(function () {
    event.preventDefault();
    console.log("Izmene");
    const $form = $(this);
    const $inputs = $form.find('input, select, button, textarea');
    const serializedData = $form.serialize();
    console.log(serializedData);
    $inputs.prop('disabled', true);

    // kreirati request za UPDATE handler

    request.done(function (response, textStatus, jqXHR) {


        if (response === 'Success') {
            console.log('Kolokvijum je izmenjen');
            location.reload(true);
            //$('#izmeniForm').reset;
        }
        else console.log('Kolokvijum nije izmenjen ' + response);
        console.log(response);
    });

    request.fail(function (jqXHR, textStatus, errorThrown) {
        console.error('The following error occurred: ' + textStatus, errorThrown);
    });


    //$('#izmeniModal').modal('hide');
});

$('#btn-pretraga').click(function () {

    var para = document.querySelector('#myInput');
    console.log(para);
    var style = window.getComputedStyle(para);
    console.log(style);
    if (!(style.display === 'inline-block') || ($('#myInput').css("visibility") ==  "hidden")) {
        console.log('block');
        $('#myInput').show();
        document.querySelector("#myInput").style.visibility = "";
    } else {
       document.querySelector("#myInput").style.visibility = "hidden";
    }
});

$('#btn').click(function () {
    $('#pregled').toggle();
});

$('#btnDodaj').submit(function () {
    $('#myModal').modal('toggle');
    return false;
});

$('#btnIzmeni').submit(function () {
    $('#myModal').modal('toggle');
    return false;
});

