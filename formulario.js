 
        //Objeto para pegar a referencia do campo de texto
        //colocando o foco no campo de nome facilita o preenchimento do formulario
        document.getElementById("nomeDigitado").focus();
        
        function validarFormulario(){
            let nome = document.getElementById("nomeDigitado").value;
            let Email = document.getElementById("emailDigitado").value;
            let Endereço = document.getElementById("endereçoDigitado").value;
           
            
                
                if((nome === "") && (Email === "") && (Endereço === "")){
                    alert("Preencha todos os campos")
                document.getElementById("nomeDigitado").style.boxShadow = "3px 3px 5px 3px red"
                document.getElementById("emailDigitado").style.boxShadow = "3px 3px 5px 3px red"
                document.getElementById("endereçoDigitado").style.boxShadow = "3px 3px 5px 3px red"          
              }else if ((nome === "")  ){
                alert("Preencha o campo Nome")
                document.getElementById("nomeDigitado").style.boxShadow = "3px 3px 5px 3px red"
                     return false;
              }
             else if ((Email === "") ){
                alert("Preencha o campo Email")
                document.getElementById("emailDigitado").style.boxShadow = "3px 3px 5px 3px red"
                     return false;   
             }else if ((Endereço === "") ){
                alert("Preencha o campos Endareço")
                document.getElementById("endereçoDigitado").style.boxShadow = "3px 3px 5px 3px red"
                     return false;
              }else if ((nome !== "") && (Email !== "") && (Endereço !== "")) {
                alert("Você completo o formulario Parabens!!" )
                document.getElementById("nomeDigitado").style.boxShadow = "3px 3px 5px 3px green"
                document.getElementById("emailDigitado").style.boxShadow = "3px 3px 5px 3px green"
                document.getElementById("endereçoDigitado").style.boxShadow = "3px 3px 5px 3px green"       
                return true
              }else{}





        
           

       
              return false;
              
    }    

    