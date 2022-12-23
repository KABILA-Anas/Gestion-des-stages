<?php require('back_end/connexion.php');


    ///*** Formations
    // lst
    $Smt=$bdd->prepare("SELECT ID_FORM,FILIERE FROM formation WHERE TYPE_FORM=?");
    $Smt->execute(array('0')); 
    $rows = $Smt->fetchAll(PDO::FETCH_ASSOC);
    // cycle
    $Smt1=$bdd->prepare("SELECT ID_FORM,FILIERE FROM formation WHERE TYPE_FORM=?");
    $Smt1->execute(array('1')); 
    $rows1 = $Smt1->fetchAll(PDO::FETCH_ASSOC);
    // Mst
    $Smt2=$bdd->prepare("SELECT ID_FORM,FILIERE FROM formation WHERE TYPE_FORM=?");
    $Smt2->execute(array('2')); 
    $rows2 = $Smt2->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <link rel="stylesheet" href="signup.css">
    <title>Signup</title>
</head>
<body>
    <div id="page" class="site">
        <div class="container">
            <div class="form-box">
                <div class="progress">
                    <div class="logo"><a href=""><span>FST</span>AGE</a></div>
                    <ul class="progress-steps">
                        <li class="step active">
                            <span>1</span>
                            <p>Personal<br></p>
                        </li>
                        <li class="step">
                            <span>2</span>
                            <p>Contact<br></p>
                        </li>
                        <li class="step">
                            <span>3</span>
                            <p>Studies<br></p>
                        </li>
                        <li class="step">
                            <span>4</span>
                            <p>Security<br></p>
                        </li>
                    </ul>
                </div>
                <form action="back_end/Signup_Etu.php" method="post" enctype="multipart/form-data" id="form">
                    <div class="form-one form-step active">
                        <div class="bg-svg"></div>
                        <h2>Personal Information</h2>
                        <p>Enter your personal information correctly</p>
                        <div class="containerim">
                            
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' name="imageUpload" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload"></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url('icons/avatar.png');">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="prenom_etu">First Name</label>
                            <input class="step1_input name" type="text" name="prenom_etu" id="prenom_etu" placeholder="e.g. John" required>
                        </div>
                        <div>
                            <label for="nom_etu">Last Name</label>
                            <input class="step1_input name" type="text"  name="nom_etu" id="nom_etu" placeholder="e.g. Paul" required>
                        </div>
                        <div class="birth">
                            <label for="id">Date of Birth</label>
                            <div class="grouping">
                                <input class="step1_input day" type="text" pattern="[0-9]*" name="day" id="day" value="" min="0" max="31" placeholder="DD" required>
                                <input class="step1_input month" type="text" pattern="[0-9]*" name="month" id="month" value="" min="0" max="12" placeholder="MM" required>
                                <input class="step1_input year" type="text" pattern="[0-9]*" name="year" id="year" value="" min="0" placeholder="YYYY" required>
                                
                            </div>
                            <span id="date" style="color:red; display:none;" >ex: 01 01 2000<span>
                        </div>
                        <div>
                            <label for="cin">CIN</label>
                            <input class="step1_input" type="text"  name="cin" id="cin" placeholder="" required>
                        </div>
                        <div>
                            <label for="">CV</label>
                            <button type = "button" class = "btn-warnin">
                                <i class = "fa fa-upload"></i> Upload File
                                <input type="file"  name="cvUpload">
                              </button>
                        </div>
                    </div>
                    
                    <div class="form-two form-step">
                        <div class="bg-svg"></div>
                        <h2>Contact</h2>
                        <div>
                            <label for="number">Phone</label>
                            <input class="step2_input" type="text" name="number" id="number" placeholder="+212xxxxxxxxx"required>
                        </div>
                        <div>
                            <label for="adress">Adress</label>
                            <input class="step2_input" type="text" name="adress" id="adress" placeholder="Street Adress" required>
                        </div>
                        <div>
                            <label for="city">City</label>
                            <input class="step2_input" type="text" name="city" id="city" placeholder="City" required>
                        </div>
                       
                        
                       
                    </div>
                    <div class="form-three form-step">
                        <div class="bg-svg"></div>
                        <h2>Studies</h2>
                        <div>
                            <label for="cne">CNE</label>
                            <input class="step3_input" type="text" name="cne"  id="cne" placeholder="" required>
                            <span id="cne_msg" style="color:red; display:none;" >ex: R112020203<span>
                        </div>
                        <div style="display: inline-block !important; ">
                            <label for="type">Type</label>
                            <select class="step3_input" name="type" id="type_filiere" required>
                                <option value="">Please select</option>
                                <option value="1" selected>Cycle</option>
                                <option value="2">Master</option>
                                <option value="0">Liscence</option>
                            </select>
                            <label for="filière" style="margin-left: 25px;">Filière</label>
                            <select name="filière_cyc" id="filiere_Cycle" >
                                <option value="">Select Cycle</option>
                                <?php foreach($rows1 as $cycle): ?>
                                <option value="<?php print($cycle['ID_FORM']); ?>"><?php print($cycle['FILIERE']); ?></option> 
                                <?php endforeach; ?>  
                            </select>
                            <select name="filière_lst" id="filiere_LST" style="display:none;" >
                                <option value="">Select LST</option>
                                <?php foreach($rows as $lst): ?>
                                <option value="<?php print($lst['ID_FORM']); ?>"><?php print($lst['FILIERE']); ?></option> 
                                <?php endforeach; ?>  
                            </select>
                            <select name="filière_mst" id="filiere_Master" style="display:none;" >
                                <option value="">Select Master</option>
                                <?php foreach($rows2 as $Mst): ?>
                                <option value="<?php print($Mst['ID_FORM']); ?>"><?php print($Mst['FILIERE']); ?></option> 
                                <?php endforeach; ?>  
                            </select>
                        </div>
                        <div id="niv_cycle">
                            <label for="niveau">Niveau</label>
                            <select name="niveau_cyc" id="niveau_cycle" >
                                <option >Please select</option>
                                <option value="1">1er anneé</option>
                                <option value="2">2ème anneé</option>
                                <option value="3">3ème anneé</option>
                            </select>
                        </div>  
                        <div style="display:none;" id="niv_master">
                            <label for="niveau">Niveau</label>
                            <select name="niveau_mst" id="niveau_master" >
                                <option >Please select</option>
                                <option value="1">1er anneé</option>
                                <option value="2">2ème anneé</option>
                            </select>
                        </div>     
                        <div>
                            <label for="promo">Promotion</label>
                            <select name="promo" id="promo" >
                                <option >Please select</option>
                                <option value="<?php print(date("Y")-2);?>"><?php print(date("Y")-2);?></option>
                                <option value="<?php print(date("Y")-1);?>"><?php print(date("Y")-1);?></option>
                                <option value="<?php print(date("Y"));?>"><?php print(date("Y"));?></option>
                            </select>
                        </div>       
                    </div>
                    <div class="form-four form-step">
                        <div class="bg-svg"></div>
                        <h2>Security</h2>
                        <div>
                            <label for="email">Email</label>
                            <input type="email" name="user_mail" id="email" placeholder="Your email address" required>
                            <span id="email_msg" style="color:red; display:none;" >ex: user@mail.xyz<span>
                        </div>
                        <div>
                            <label for="pass">Password</label>
                            <input type="password" class="pass" name="pass" id="pass" placeholder="Password" required>
                            <span id="pass1_msg" style="color:red; display:none;" >more than 8 chararacters<span>
                        </div>
                        <div>
                            <input type="password" class="pass" id="confirm_pass" placeholder="Confirm Password" required>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox">
                            <label for="">Please Confirm You Are Not a Robot</label>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn-prev" disabled>Back</button>
                        <button type="button" class="btn-next" id="button_next" disabled>Next Step</button>
                        <button type="submit" class="btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <script src="signup.js"></script> -->
    <script>


        var type_filiere = $('#type_filiere');
        var cycle = $('#filiere_Cycle');
        var lst = $('#filiere_LST');
        var master = $('#filiere_Master');
        var niv_cycle = $('#niv_cycle');
        var niv_master = $('#niv_master');
        var button_next = $('.btn-next');
        var button_prev = $('.btn-prev');
        var number = 0;
        var page = 1;
        var inputs = $('form#form :input');
        var input_name = $('#prenom_etu');

        const nextButton = document.querySelector('.btn-next');
const prevButton = document.querySelector('.btn-prev');
const steps = document.querySelectorAll('.step');
const form_steps = document.querySelectorAll('.form-step');
let active = 1;

nextButton.addEventListener('click', () => {
    active++;
    if(active > steps.length) {
        active = steps.length;
    }
    updateProgress();
})

prevButton.addEventListener('click', () => {
    active--;
    if(active < 1) {
        active = 1;
    }
    updateProgress();
})

const updateProgress = () => {
    steps.forEach((step, i) => {
        if(i == (active-1)) {
            step.classList.add('active');
            form_steps[i].classList.add('active');
            console.log('i =>' +i);
            number = i;
        } else {
            step.classList.remove('active');
            form_steps[i].classList.remove('active');
        }
    });

    //enable or disable prev and next buttons
    if(active === 1) {
        prevButton.disabled = true;
    } else if(active === steps.length) {
        nextButton.disabled = true;
    } else {
        prevButton.disabled = false;
        nextButton.disabled = false;
    }
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});


        type_filiere.on('load change', function () {
            if( this.value == '1' )
            {
                cycle.show();
                lst.hide();
                master.hide();
                niv_cycle.show();
                niv_master.hide();
            }
            else if( this.value == '2' )
            {
                cycle.hide();
                lst.hide();
                master.show();
                niv_cycle.hide();
                niv_master.show();
            }
            else if( this.value == '0' )
            {
                cycle.hide();
                lst.show();
                master.hide();
                niv_cycle.hide();
                niv_master.hide();
            }

        })

        
            
            // var init = 0;
            // var fin = 0;
            // var is_empty = false;

            // console.log(number);

            
            // function num_update(){
                
            //     switch(number) {
            //     case 0:
            //         init = 1;
            //         fin = 7;
            //         break;
            //     case 1:
            //         init = 8;
            //         fin = 11;
            //         break;
            //     case 2:
            //         init = 11;
            //         fin = 12;
            //         break;
            //     default:
                    
            // }
            // }


            
            var is_empty = false;

            

            function disable_next(class_name,event)
            {
                $(class_name).on(event, function() {
                            
                            //console.log(this.value);
            
                            is_empty = false;
            
                            $(class_name).each(function () {
                                if ( this.value == '' )
                                    is_empty = true;
                            })
                            
                            if(is_empty == false)
                                button_next.prop("disabled",false);
                            else
                                button_next.prop("disabled",true);
            
            
            
                });
            }

            
            disable_next(".step1_input","focus"+" keyup");

            disable_next(".step2_input","focus"+" keyup");

            disable_next(".step3_input","focus"+" keyup"+" change");



            button_next.on('click', function(){
                is_empty = false;

                if( number == 1 )
                {
                    $(".step2_input").each(function () {
                    if ( this.value == '' )
                        is_empty = true;
                    })
                }
                else if( number == 2 )
                {
                    $(".step3_input").each(function () {
                    if ( this.value == '' )
                        is_empty = true;
                    })
                }

                if( is_empty == true )
                    button_next.prop("disabled",true);
                else
                    button_next.prop("disabled",false);
            })

            // button_prev.on('click', function(){
            //     button_next.prop("disabled",false);
            // })

            function enable_button(button){
                button.prop("disabled",false);
            }

            function disable_button(button){
                button.prop("disabled",true);
            }

            button_prev.on('click', function(){
                enable_button(button_next);
            })
            

            var sumbit = $(".btn-submit");

            function verifier(regularExp,inputValue,class_name)
            {
                
                if(regularExp.test(inputValue))
                {
                    $(class_name).css(
                    {
                        "border-color" : "#54BE4A"                           
                    })
                    if( number == 3 )
                        enable_button(sumbit);
                    return true;
                }
                
                $(class_name).css(
                {
                    "border-color" : "red"
                });
                if( number == 3 )
                    disable_button(sumbit);
                else
                    disable_button(button_next);
                
                return false;    
            }

            $("form").on('focus keyup',function()
            {
                // body...
                //var regEx_name = /^[A-Z][a-zA-Z]{1,20}( [A-Z][a-zA-Z]{0,20})*$/;
                var regEx_month = /(0[1-9]|1[012])$/;
                var regEx_day = /(0[1-9]|1[0-9]|2[0-9]|3[01])$/;
                var regEx_year = /(19[0-9][0-9]|2[0-9][0-9][0-9])$/;
                var regEx_cne = /(^[a-zA-Z][0-9]{9})$/;
                //var regEx_cin = /(^[a-zA-Z][a-zA-Z][0-9]{5})$/;
                var regEx_mail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                var regEx_pass = /.{8,}$/;
                var regEx_phone = /[^a-z][^A-Z]$/;

                var day = (document).getElementById('day');
                var month = (document).getElementById('month');
                var year = (document).getElementById('year');
                var cne = (document).getElementById('cne');
                var mail = (document).getElementById('email');
                var phone = (document).getElementById('number');
                var pass1 = (document).getElementById('pass');
                var pass2 = (document).getElementById('confirm_pass');

                

                if( number == 0 )
                {
                    if( verifier(regEx_day,day.value,day) &&
                        verifier(regEx_month,month.value,month) &&
                        verifier(regEx_year,year.value,year)
                      )
                        $('#date').hide();
                    else
                        $('#date').show();
                }
                else if( number == 1 )
                {
                    verifier(regEx_phone,phone.value,phone);
                }
                else if( number == 2 )
                {
                    if( verifier(regEx_cne,cne.value,cne) )
                        $('#cne_msg').hide();
                    else
                        $('#cne_msg').show();
                    
                }
                else if( number == 3 )
                {
                    if(verifier(regEx_mail,mail.value,mail))
                        $('#email_msg').hide();
                    else
                        $('#email_msg').show();

                    if(verifier(regEx_pass,pass1.value,pass1))
                        $('#pass1_msg').hide();
                    else
                        $('#pass1_msg').show();

                    if(pass1.value == pass2.value)
                    {
                        $(pass2).css(
                        {
                            "border-color" : "#54BE4A"
                            
                        })
                        enable_button(sumbit);
                    }
                    else
                    {
                        $(pass2).css(
                        {
                            "border-color" : "red"
                        });
                        disable_button(sumbit);
                    }
                }
                
                
 
            })

           


    </script>
</body>
</html>