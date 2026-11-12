"use strict";
let path = [];
path = location.toString().split('\/');
//var pathAjax = path[0]+'//'+path[2]+'/'+path[3]+'/ajax/'
const pathAjax = path[3];
///let yiiOptions = {};
//alert(pathAjax);

//if(yiiOptions.appName){
 //   alert(yiiOptions.appName);
//}

function inter_init() {

//    document.getElementById('searchform-id').focus();
    const objSelectD=document.getElementById('navigatorform-enterprise');
    const options = objSelectD.options;
    if(!options.length){
        $.ajax({
            type: "POST",
            url:"/yii2-personal/web/json/department/get-enterprises",
            data:{},
            dataType: "json",
            success: function(data,textStatus){
                //     console.log(data);//value
                createEnterpriseOptions(data.enterprises);
            }
        });
    }

}

function getDepartments(ent){
    $.ajax({
        type: "POST",
        url:"/yii2-personal/web/json/department/get-departments",
        data:{'ent':ent},
        dataType: "json",
        success: function(data,textStatus){

            createDepartmentOption(data.departments);
        }
    });
}
function getOffices(dep){
    $.ajax({
        type: "POST",
        url:"/yii2-personal/web/json/department/get-offices",
        data:{'dep':dep},
        dataType: "json",
        success: function(data,textStatus){

            createOfficesOption(data.offices);
        }
    });
}
function getSections(of){
    $.ajax({
        type: "POST",
        url:"/yii2-personal/web/json/department/get-sections",
        data:{'of':of},
        dataType: "json",
        success: function(data,textStatus){
            createSectionsOption(data.sections);
        }
    });
}
window.onload=inter_init;

function createEnterpriseOptions(ar){
    const objSelectD=document.getElementById('navigatorform-enterprise');
//    removeAllOptions(objSelectD);
//    var divisionSelected=readCookie('divisionSelected');
    let entSel = readCookie('enterprise-value');
    for(let i=0;i<ar.length;i++){
        const opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }
 //   $('#navigatorEnterprise option[value=5]').attr('selected','selected');

    if(entSel){
        for(let i=0;i<objSelectD.options.length;i++){
            if(objSelectD.options[i].value === entSel){
                objSelectD.options[i].setAttribute('selected','selected');
            }
        }
    }
    const enterpriseSelected = objSelectD[objSelectD.selectedIndex].value;
    getDepartments(enterpriseSelected);
//    createDivisionOptions();
}

function createDepartmentOption(ar){
    let objSelectD = document.getElementById('navigatorform-department');
//    removeAllOptions(objSelectD);
  //  $('#navigatorform-department option').remove();
    for(let i=0;i<ar.length;i++){
        let opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }
    const depSel = readCookie('department-value');
    if(depSel){
        for(let i=0;i<objSelectD.options.length;i++){
            if(objSelectD.options[i].value === depSel){
                objSelectD.options[i].selected = true;
            }
        }
    }
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    getOffices(departmentSelected);
}
function createOfficesOption(ar){
    let objSelectD = document.getElementById('navigatorform-office');
//    removeAllOptions(objSelectD);
 //   $('#navigatorform-office option').remove();
    if(ar.length === 0) return;
    let opt = document.createElement('option');
    opt.text = 'all';
    opt.value = 0;
    objSelectD.options.add(opt);
    for(let i=0;i<ar.length;i++){
        opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }
    const depSel = readCookie('office-value');
    if(depSel){
        for(let i=0;i<objSelectD.options.length;i++){
            if(objSelectD.options[i].value === depSel){
                objSelectD.options[i].selected = true;
            }
        }
    }
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    getSections(departmentSelected);


    /* const depSel = readCookie('departmentValue');
     if(depSel){
         for(let i=0;i<objSelectD.options.length;i++){
             if(objSelectD.options[i].value === depSel){
                 objSelectD.options[i].selected = true;
             }
         }
     }*/
}
function createSectionsOption(ar){
    let objSelectD = document.getElementById('navigatorform-section');
//    removeAllOptions(objSelectD);
 //   $('#navigatorform-section option').remove();
    if(ar.length === 0) return;
    let opt = document.createElement('option');
    opt.text = 'all';
    opt.value = 0;
    objSelectD.options.add(opt);
    for(let i=0;i<ar.length;i++){
        let opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }
     const depSel = readCookie('section-value');
     if(depSel){
         for(let i=0;i<objSelectD.options.length;i++){
             if(objSelectD.options[i].value === depSel){
                 objSelectD.options[i].selected = true;
             }
         }
     }
}

function changeEnterprise(){
    const objSelectD=document.getElementById('navigatorform-enterprise');
    const enterpriseSelected = objSelectD[objSelectD.selectedIndex].value;
	createCookie('enterprise-value',enterpriseSelected,360);
    $('#navigatorform-department option').remove();
    $('#navigatorform-office option').remove();
    $('#navigatorform-section option').remove();

    getDepartments(enterpriseSelected);


}


function removeAllOptions(selectbox){
    for(let i=selectbox.options.length-1;i>=0;i--){
        selectbox.remove(i);
    }
}
function changeDepartment() {
    const objSelectD=document.getElementById('navigatorform-department');
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    createCookie('department-value',departmentSelected,360);
    $('#navigatorform-office option').remove();
    $('#navigatorform-section option').remove();
//    console.log(departmentSelected);
    getOffices(departmentSelected);
}
function changeOffice() {
    const objSelectD=document.getElementById('navigatorform-office');
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    createCookie('office-value',departmentSelected,360);
 //   $('#navigatorform-section option').remove();
    const objSelectS=document.getElementById('navigatorform-section');
    $('#navigatorform-section option').remove();
 //   console.log(departmentSelected);
    getSections(departmentSelected);
}

function changeSection() {
    const objSelectD=document.getElementById('navigatorform-section');
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    createCookie('section-value',departmentSelected,360);
    //   console.log(departmentSelected);

}
function readCookie(name){
    let nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i=0;i<ca.length;i++) {
        let c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function createCookie(name,value){
    const date = new Date();
    date.setTime(date.getTime()+(20*24*60*60*1000));
    let expires = "; expires=" + date.toGMTString();
    document.cookie = name + "=" + value + expires + "; path=/";
}
