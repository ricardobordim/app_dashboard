$(document).ready(() => {
    $('#documentacao').on('click',()=>{
        // console.log('Link Documetação Clicado')
        // O load faz um get simplicado
        // $('#pagina').load('documentacao.html')

        // get espera uma url e uma ação
        $.get('documentacao.html', dados =>{
            // console.log(dados);
            $('#pagina').html(dados)
        })
    })
    $('#suporte').on('click', () => {
        // console.log('Link Suporte Clicado')
        // $('#pagina').load('suporte.html')

        // Agora fazendo por Post
        $.post('suporte.html', dados => {
            // console.log(dados);
            $('#pagina').html(dados)
        })
    })

    // Ajax
    $('#competencia').on('change',e =>{
        // console.log($(e.target).val())

        // O metodo ajax espera um obj literal com atributos especificos

        // atributos =  método, url, dados, sucesso, erro

        let competencia = $(e.target).val()
        // console.log(competencia)


        // retorno default é html puro, mas pode ser modificado
        $.ajax({
            type : 'GET',
            url : 'app.php',
            // data : 'competencia=2018-10&x=10&y=50', 
            data: `competencia=${competencia}`, 
            dataType:'json',

            //x-www-form-urlencoded
            // success: ()=>{console.log('Sucesso')},
            // success: dados => { console.log(dados) },
            success: dados => { 
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)

                // console.log(dados.numeroVendas, dados.totalVendas) 
                },


            // error: ()=>{console.log('Erro')}
            error: erro => { console.log(erro) }

        });

    })


	
})
