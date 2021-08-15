
      
      // stripe initialization
      var stripe = Stripe('pk_test_51JFmtTKAPbSDHTVapG4opJYg04GO6OcQrXnAaR6R9VOpJgNLWrQDh3dtFZz2ChhWhdTiejLXGVCc0aH9lDaaqLe700KEM9V5bb');
      var elements = stripe.elements();
      var style = {
        base: {
          color: "#32325d",
        }
      };
      
      // stripe injection to DOM
      var card = elements.create("card", { style: style });
      card.mount("#card-element");

    
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.querySelectorAll('.needs-validation')
      var checkoutForm = document.getElementById('payment-form');
      var submitButton = document.getElementById('submitButton');
    

      submitButton.addEventListener('click', function(){

        forms.forEach(form => {
          
          if (!form.checkValidity()) {

            console.log("error");
            
          } else{
            
            // disable form content
            Array.from(form.elements).forEach(formElement => formElement.disabled = true);

            let displayError = document.getElementById('card-errors');
            stripe.confirmCardPayment(form.dataset.secret, {
            payment_method: {
              card: card
            }
          }).then(function(result) {

              // reenable form  content
              Array.from(form.elements).forEach(formElement => formElement.disabled = false);

            if (result.error) {

              // Show error to your customer (e.g., insufficient funds)
              displayError.textContent = result.error.message;

            } else {
              // The payment has been processed!
              if (result.paymentIntent.status === 'succeeded') {
                checkoutForm.submit();
              }
            }
          });
          }
          
          form.classList.add('was-validated')


        });

      });
      

      
