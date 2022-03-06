const URL = location.href;


$(document).ready(function(){
    const stdForm = $('form.student-form')
    const stdDetails = {}

    // on input first name
    stdForm.find('input[name="stdFirstName"]:first').on('keyup', function(){
        stdDetails[this.name] = this.value
        fillFullName()
    })

    // on input last name
    stdForm.find('input[name="stdLastName"]:first').on('keyup', function(){
        stdDetails[this.name] = this.value
        fillFullName()
    })


    // fill student full name
    const stdFullName = stdForm.find('input[name="stdFullName"]:first')
    function fillFullName(){
        stdFullName.val(
            (stdDetails.stdFirstName || '')
            +
            (stdDetails.stdFirstName && stdDetails.stdLastName ? ' ' + stdDetails?.stdLastName : '')
        )
    }

    // on select school
    const gradesOptions = [],
    schoolGrades = {},
    inputsCondFields = {
        // school,grades : [inputs]
    },
    stdSchool = stdForm.find('select[name="stdSchool"]:first'),
    selectGrade = stdForm.find('select[name="stdGrade"]:first'),
    condFields = stdForm.find('div#condFields:first');


    $.when(

        $.getJSON(URL+"server/grades_list.php?type=get",data=>{
            if(data){
                if(data.data){
                    for(let g of data.data){
                        g = g.toUpperCase()
                        gradesOptions.push(g)
                        selectGrade.append(new Option(g, g))
                    }
                }
            }
        }),

        $.getJSON(URL+"server/school_list.php?type=get",data=>{
            if(data){
                if(data.data){
                    for(let s of data.data){
                        s = s.toLowerCase()
                        stdSchool.append(new Option(s, s))
                        switch(s){
                            case 'elementary':
                                schoolGrades[s] = gradesOptions[0];
                                break;
                            case 'middle':
                                schoolGrades[s] = gradesOptions[1];
                                break;
                            case 'high':
                                schoolGrades[s] = gradesOptions[2];
                                break;
                            default:
                                schoolGrades[s] = '';
                        }   
                    }

                    inputsCondFields['elementary,GR 0 - GR 5'] = ['age'];
                    inputsCondFields['middle,GR 6 - GR 8'] = ['age','email'];
                    inputsCondFields['middle,GR 9 - GR 12'] = ['age','email','phone'];

                }
            }
        })
            
    ).then(_=>{

        stdSchool.on('change', function(){
            stdDetails[this.name] = this.value
            if(schoolGrades[this.value]){
                selectGrade.val(schoolGrades[this.value])
                stdDetails.stdGrade = schoolGrades[this.value]
            }else{
                stdDetails.stdGrade = '';
            }
            addNewFields()
        })
    
        selectGrade.on('change',function(){
            stdDetails[this.name] = this.value
            addNewFields()
        })
    
        // add new Fields
        function addNewFields(){
            condFields.empty()
            for(let del in stdDetails){
                if(del == 'stdAge' || del == 'stdEmail' || del == 'stdPhone'){
                    delete stdDetails[del]
                }
            }
            const FormGroup = (el,label) =>{
                let fg = $(`<div class='col-${3} form-group'/>`)
                if(label){
                    fg.append(`<label>${label}</label>`)
                }
                el.addClass('form-control')
                fg.append(el)
                condFields.append(fg)
            },
            Age = _=>{
                let age = $('<input>', {type:'number', name:'stdAge', min:0, max:90, step:1, value:0,required:true})
                age.on('keyup',function(e){
                    if(!((e.keyCode > 95 && e.keyCode < 106) || (e.keyCode > 47 && e.keyCode < 58) || e.keyCode == 8)) {
                        this.value = stdDetails[this.name]
                        return false;
                    }else{
                        stdDetails[this.name] = this.value
                    }
                })
                FormGroup(age, 'Student Age')
            },
            Email = _=>{
                let email = $('<input>', {type:'email', name:'stdEmail', value:'',required:true})
                email.on('keyup', function(){
                    stdDetails[this.name] = this.value
                })
                FormGroup(email, 'Student Email')
            },
            Phone = _=>{
                let phone = $('<input>', {type:'tel', name:'stdPhone', value:'',required:true})
                phone.on('keyup', function(){
                    stdDetails[this.name] = this.value
                })
                FormGroup(phone, 'Student Phone')
            }
        
            for(let cond in inputsCondFields){
                let condArr = cond.split(',')
                if(condArr[0] == stdDetails.stdSchool && condArr[1] == stdDetails.stdGrade){
                    for(let k of inputsCondFields[cond]){
                        switch(k){
                            case 'age':
                                Age()
                                break;
                            case 'email':
                                Email()
                                break;
                            case 'phone':
                                Phone()
                                break;
                        }
                    }
                    if(inputsCondFields[cond].length === 2)condFields.append(`<div class='col-3 form-group'/>`);
                    else condFields.css({ justifyContent:'space-between'});
                    break;
                }
            }
        }

    }).catch(err=>{
        console.error(err)
    })





    // on form reset
    stdForm.on('reset',_=>{
        for(let k in stdDetails)delete stdDetails[k];
        stdForm.find('p#errorMsg:first').empty()
        condFields.empty()
    })

    // on form continue
    stdForm.find('button#btnContinue:first').click(_ => {
        const inD = $(this).find('div#inputDetails:first'),
            vD = stdForm.find('div#viewDetails:first'),
            errMsg = 'p#errorMsg:first';
        let flag = true;
        
        inD.clone().children('div.row').each(function () {
            if(!flag)return true;

            let v = $(this)
            
            if(v.children().length){
                v.css({marginTop:20})
            }

            // input,select replace with p tag
            v.find('input,select').each(function () {
                if(!flag)return true;

                let $v = $(this),
                    val = $v.val(),
                    isSelect = false
                if($v.attr('required')){ 
                    if($v.is('select')){
                        val = stdForm.find(`select[name="${$v.attr('name')}"]:first`).val()
                        isSelect = true
                    }else{
                        isSelect = false
                    }

                    if(!val || val == 0){
                        let lbl = $v.parent().find('label:first').text()
                        if(isSelect)lbl = 'Please Select ' + lbl.replace('Select ','');
                        else lbl = lbl + ' is required'
                        stdForm.find(errMsg).text(lbl)
                        flag = false;
                    }
                }
                $v.replaceWith($(`<p />`,{ css: { fontWeight: 'bold' } , text: val ,...isSelect && { class: 'text-capitalize' } }))
                stdDetails[$v.attr('name')] = val
            })


            // if btnReset|| btnContinue
            let btnReset = v.find('button#btnReset:first'),
                btnContinue = v.find('button#btnContinue:first')

            if(btnReset){
                // btnReset replace with back button
                let bck = $(`<button class='btn btn-light' id='btnBack'>Back</button>`)
                btnReset.replaceWith(bck)
                bck.click(_=>{
                    vD.empty()
                    inD.show()
                })
            }

            if(btnContinue){
                // btnContinue replace with submit button
                let sub = $(`<button class='btn btn-success' id='btnSubmit'>Submit</button>`)
                btnContinue.replaceWith(sub)
            }
            
            v.find(errMsg).empty()

            vD.append(v)
        })

        // check mother or father email is filled
        if(flag && !stdDetails['stdMotherEmail'] && !stdDetails['stdFatherEmail']){
            flag = false;
            stdForm.find(errMsg).text(`Mother or Father Email is required`)
        }

        if(flag){
            stdForm.find(errMsg).empty()
            inD.hide()
            vD.show()
        }else{
            vD.empty()
        }

    })




    // on form submit
    var isRequesting = false;
    stdForm.on('submit', function (e) {
        e.preventDefault()
        if(!isRequesting){
            const stdDetails = $(this).serializeArray()
            isRequesting = true;
            const msg = stdForm.find('div#viewDetails p#errorMsg:first')
            msg.text('Please wait...')
            $.post(URL+'server/student_form.php', stdDetails, function (data) {
                isRequesting = false;
                if(data){
                    data = JSON.parse(data)                   
                    if(data.status == 200){
                        // reset form
                        stdForm.trigger('reset')
                        stdForm.find('div#viewDetails:first').empty().hide()
                        stdForm.find('div#inputDetails:first').show()
                        stdForm.find('p#errorMsg:first').text(data.message).fadeOut(8000)
                    }else{
                        msg.text(data.message)
                    }
                }else{
                    msg.text(data.message)
                }
            })
        }
    })

})