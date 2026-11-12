"use strict";
let path = [];
path = location.toString().split('\/');
//var pathAjax = path[0]+'//'+path[2]+'/'+path[3]+'/ajax/'
const pathAjax = path[3];


//alert('ok');

function inter_init() {

 //   document.getElementById('searchform-id').focus();
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

window.onload=inter_init;

function createEnterpriseOptions(ar){
    const objSelectD=document.getElementById('employeeform-enterprise_id');
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
    let objSelectD = document.getElementById('employeeform-department_id');
//    removeAllOptions(objSelectD);
    $('#employeeform-department_id option').remove();
    for(let i=0;i<ar.length;i++){
        let opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    getOffices(departmentSelected);
    const depSel = readCookie('department-value');
    if(depSel){
        for(let i=0;i<objSelectD.options.length;i++){
            if(objSelectD.options[i].value === depSel){
                objSelectD.options[i].selected = true;
            }
        }
    }
}
function createOfficesOption(ar){
    let objSelectD = document.getElementById('employeeform-office_id');
//    removeAllOptions(objSelectD);
    $('#employeeform-office_id option').remove();
    if(ar.length === 0) return;
    for(let i=0;i<ar.length;i++){
        let opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }
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
    const objSelectD=document.getElementById('employeeform-enterprise_id');
    const enterpriseSelected = objSelectD[objSelectD.selectedIndex].value;
    createCookie('enterprise-value',enterpriseSelected,360);

    getDepartments(enterpriseSelected);
    $('#navigatorOffice option').remove();

}


function removeAllOptions(selectbox){
    for(var i=selectbox.options.length-1;i>=0;i--){
        selectbox.remove(i);
    }
}
function changeDepartment() {
    const objSelectD=document.getElementById('employeeform-department_id');
    const departmentSelected = objSelectD[objSelectD.selectedIndex].value;
    createCookie('department-value',departmentSelected,360);
//    console.log(departmentSelected);
    getOffices(departmentSelected);
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
