/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function validateMandatoryFields(formId){
    var submit = true;
    var mandatoryFields = document.forms[formId].getElementsByClassName("mandatory");
    
    for (var i = 0; i < mandatoryFields.length; i++) {
        var str = mandatoryFields[i].value.trim();
        if (str== "") {
            mandatoryFields[i].value = "";
            mandatoryFields[i].style.borderColor = "red";
            submit = false;
        }
    }
    return submit;
}

//function formValidation(formId) {
//    var submit = validateMandatoryFields(formId);
//    
//    if (submit)
//        document.getElementById(formId).submit();
//    else
//        alert("Please enter all the mandatory fields.");
//}

function cancelAction()
{
    window.location="http://localhost/BugHound/homePage.php";
}

function loadRelease(program)
{
    var ajaxRequest = new XMLHttpRequest();
    
    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('release');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
        }
    }

    ajaxRequest.open("GET", "releaseAndVersionAjax.php?program=" + program, true);
    ajaxRequest.send(null);
}

function loadFunctionalArea(programId,clearMessage)
{
    //alert("loadFunctionalArea");
    if(clearMessage)
        document.getElementById("message").innerHTML="";
    var ajaxRequest = new XMLHttpRequest();
    
    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('functionalAreaList');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
        }
    }

    ajaxRequest.open("GET", "functionalAreaAjax.php?programId=" + programId, true);
    ajaxRequest.send(null);
}

function checkForDuplicateFunctionalArea(functionalAreaName)
{
    if(document.getElementById('functionalAreasForProgram'))
    {
        var functionAreaListFromDB = document.getElementById('functionalAreasForProgram').value;
        var functionAreaList = functionAreaListFromDB.split(",");
        for(var i = 0; i<functionAreaList.length; i++)
        {
            if(functionalAreaName == functionAreaList[i])
            {
                return true;
            }

        }
    }
    return false;
}

function saveFunctionalArea(formId)
{
    var submit = validateMandatoryFields(formId);
    if(submit)
    {
        var functionalAreaName = document.getElementById('functionalArea').value.trim();
        var duplicate = checkForDuplicateFunctionalArea(functionalAreaName);
        if(duplicate)
            alert("Functional Area already present for the Program");
        else
        {
            var ajaxRequest = new XMLHttpRequest();
            var programName = document.getElementById('programName').value;
            //var functionalArea = document.getElementById('functionalArea').value;

            ajaxRequest.onreadystatechange = function () {
                if (ajaxRequest.readyState == 4) {
                    var ajaxDisplay = document.getElementById('message');
                    ajaxDisplay.innerHTML = ajaxRequest.responseText;
                    loadFunctionalArea(programName,false);
                }
            }

            ajaxRequest.open("GET", "saveOrUpdateFunctionalArea.php?programName="+programName+"&functionalArea="+functionalAreaName, true);
            ajaxRequest.send(null);
        }
    }
    else
        alert("Please enter all the mandatory fields.");
    
}

function updateFunctionalArea(fieldNum,functionalAreaId)
{
    var fieldId = "functionalArea"+fieldNum;
    var functionalAreaName = document.getElementById(fieldId).value.trim();
    
    if(functionalAreaName == ""){
        alert("Please enter a valid name for functional area before Update.")
    }
    else{
        var duplicate = checkForDuplicateFunctionalArea(functionalAreaName);
        if(duplicate)
            alert("Functional Area already present for the Program");
        else
        {
            var ajaxRequest = new XMLHttpRequest();

            ajaxRequest.onreadystatechange = function () {
                if (ajaxRequest.readyState == 4) {
                    alert("Functional Area #"+functionalAreaId+" updated successfully.");
                    document.getElementById('functionalAreasForProgram').value=document.getElementById('functionalAreasForProgram').value+functionalAreaName+",";
                }
            }

            ajaxRequest.open("GET", "saveOrUpdateFunctionalArea.php?functionalAreaId="+functionalAreaId+"&functionalArea="+functionalAreaName, true);
            ajaxRequest.send(null);
        }
    }
}

function addProgram()
{
    document.getElementById('addRelease').hidden = true;
    document.getElementById('addVersion').hidden = true;
    document.getElementById('addProgram').hidden = false;
}

function addRelease()
{
    document.getElementById('addProgram').hidden = true;
    document.getElementById('addVersion').hidden = true;
    document.getElementById('addRelease').hidden = false;
}

function addVersion()
{
    document.getElementById('addProgram').hidden = true;
    document.getElementById('addRelease').hidden = true;
    document.getElementById('addVersion').hidden = false;
}

function addAttachment()
{
    var uploadSection = document.createElement('input');
    uploadSection.setAttribute('type','file');
    uploadSection.setAttribute('name','fileToUpload[]');
    
    var uploadSectionParent = document.getElementById("attachmentSection");
    //alert(uploadSectionParent.innerHTML);
    
    //uploadSectionParent.innerHTML = uploadSectionParent.innerHTML + "</br>";
    var newLine = document.createElement('br');
    
    uploadSectionParent.appendChild(newLine);
    uploadSectionParent.appendChild(uploadSection);
    //alert(uploadSectionParent.innerHTML);
}

function validateProgramName(formId)
{
    var submit = validateMandatoryFields(formId);
    
    var programName = document.forms[formId].elements[0].value.trim().toLowerCase();
    
    if (submit){
        var programList = document.getElementById('programListInDB').value;
        var arr = programList.split(',');
        
        for(var i=0;i<arr.length;i++)
        {
            var programNameDB = arr[i].toLowerCase();
            
            if(programName == programNameDB)
            {
                alert(arr[i] + " is already added. Click on 'Add New Release'");
                return false;
            }
        }
        document.getElementById(formId).submit();
    }
    else
        alert("Please enter all the mandatory fields.");
}

function validateEmployee(formId)
{
    var employee = document.getElementById('firstName').value.trim() + " " + 
                    document.getElementById('lastName').value.trim();
    var employeeName = employee.toLowerCase();
    
    var submit = validateMandatoryFields(formId);
    
    if(submit){
        var employeeList = document.getElementById('employeesDB').value;
        var arr = employeeList.split(',');
        
        for(var i=0;i<arr.length;i++)
        {
            var employeeNameDB = arr[i].toLowerCase();
            
            if(employeeName == employeeNameDB)
            {
                alert(arr[i] + " is already added.");
                return false;
            }
        }
        document.getElementById(formId).submit();
    }
    else
        alert("Please enter all the mandatory fields.");
}

function exportDataAsXml(formId)
{
    var tableName = document.getElementById('tableList').value;
    
    document.forms[formId].action = "exportDataAsXML.php";
    
    document.forms[formId].submit();
}

function checkTableName(tableName)
{
    if(tableName == "")
        document.getElementById("buttonPanel").style.display="none";
    else
        document.getElementById("buttonPanel").style.display="block";
}

function exportDataAsAscii(formId)
{
    var tableName = document.getElementById('tableList').value;
    
    document.forms[formId].action = "exportDataAsASCII.php";
    
    document.forms[formId].submit();
}