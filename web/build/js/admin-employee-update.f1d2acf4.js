"use strict";
let path = [];
path = location.toString().split('\/');
//var pathAjax = path[0]+'//'+path[2]+'/'+path[3]+'/ajax/'
const pathAjax = path[3];


//alert('ok');

function inter_init() {

    //   document.getElementById('searchform-id').focus();

}

window.onload=inter_init;


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
  //  console.log(of);
    $.ajax({
        type: "POST",
        url:"/yii2-personal/web/json/department/get-sections",
        data:{'of':of},
        dataType: "json",
        success: function(data,textStatus){
    //        console.log(data);
            createSectionsOption(data.sections);
        }
    });
}

function getOrgunit(dep, of, sec){
    $.ajax({
        type: "POST",
        url:"/yii2-personal/web/json/department/get-orgunit",
        data:{'dep':dep,'of':of,'sec':sec},
        dataType: "json",
        success: function(data,textStatus){
   //
            let orgr_unit_input = document.getElementById('employee-form-org_unit');
            orgr_unit_input.value = data.org_unit;
  //          createOfficesOption(data.offices);
        }
    });
}

window.onload=inter_init;

function createEnterpriseOptions(ar){
    const objSelectD=document.getElementById('employee-form-enterprise');
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
    let objSelectD = document.getElementById('employee-form-department');
//    removeAllOptions(objSelectD);

    for(let i=0;i<ar.length;i++){
        let opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;

    const depSel = readCookie('department-value');
    if(depSel){
        for(let i=0;i<objSelectD.options.length;i++){
            if(objSelectD.options[i].value === depSel){
                objSelectD.options[i].selected = true;
            }
        }
    }
    changeDepartment();
    getOffices(departmentSelected);
}
function createOfficesOption(ar){
    let objSelectD = document.getElementById('employee-form-office');
//    removeAllOptions(objSelectD);

    if(ar.length === 0) return;
    for(let i=0;i<ar.length;i++){
        let opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }
    changeOffice();
//    createOrgunit();
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
    let objSelectD = document.getElementById('employee-form-section');
//    removeAllOptions(objSelectD);

    if(ar.length === 0) return;
    for(let i=0;i<ar.length;i++){
        let opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }

//    createOrgunit();
    /* const depSel = readCookie('departmentValue');
     if(depSel){
         for(let i=0;i<objSelectD.options.length;i++){
             if(objSelectD.options[i].value === depSel){
                 objSelectD.options[i].selected = true;
             }
         }
     }*/
}
function changeEnterprise(){
    const objSelectD=document.getElementById('employee-form-enterprise');
    const enterpriseSelected = objSelectD[objSelectD.selectedIndex].value;
    createCookie('enterprise-value',enterpriseSelected,360);
    $('#employee-form-department option').remove();
    $('#employee-form-office option').remove();
    $('#employee-form-section option').remove();

    getDepartments(enterpriseSelected);


}


function removeAllOptions(selectbox){
    for(var i=selectbox.options.length-1;i>=0;i--){
        selectbox.remove(i);
    }
}
function changeDepartment() {
    const objSelectD=document.getElementById('employee-form-department');
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    createCookie('department-value',departmentSelected,360);
    $('#employee-form-office option').remove();
    $('#employee-form-section option').remove();

    createOrgunit();
    getOffices(departmentSelected);
}

function changeOffice() {
    const objSelectD=document.getElementById('employee-form-office');
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    createCookie('office-value',departmentSelected,360);
    /*const objSelectS=document.getElementById('employee-form-section');
    const sectionSelected = objSelectS[objSelectS.selectedIndex].value;*/
    $('#employee-form-section option').remove();
    getSections(departmentSelected);
    createOrgunit();
}

function changeSection() {
    createOrgunit();
}

function createOrgunit() {
    const objSelectD=document.getElementById('employee-form-department');
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    const objSelectO=document.getElementById('employee-form-office');
    let officeSelected = '';
    if(objSelectO[objSelectO.selectedIndex]){
        officeSelected = objSelectO[objSelectO.selectedIndex].value;
    }
    const objSelectS=document.getElementById('employee-form-section');
    let sectionSelected = '';
    if(objSelectS[objSelectS.selectedIndex]){
        sectionSelected = objSelectS[objSelectS.selectedIndex].value;
    }

    getOrgunit(departmentSelected, officeSelected,sectionSelected);
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
