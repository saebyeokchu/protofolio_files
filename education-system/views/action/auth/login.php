<script>

    window.onload = function() {    //윈도우 로딩하며 자동로그인 체크

        switch(localStorage.getItem("is")) {
            case 'master': {
                console.log('마스터 자동로그인 등록');
                autoLogIn();
                break;
            }
            case 'teacher' : {
                console.log('선생님 자동로그인 등록');
                autoLogIn();
                break;
            } 
            case 'student' : {
                console.log('학생 자동로그인 등록');
                autoLogIn();
                break;
            }
            default : {
                console.log('등록된것 없음');
            }
        }


    }

    function autoLogIn() {

        $.ajax({

            url: "/Action/auth/autoLogIn",

            type: "post", 

            data: {
                key : localStorage.getItem("authKey"),
                token : localStorage.getItem("token"),
                is : localStorage.getItem("is"),
                id : localStorage.getItem("id")
            },
            success: function(data){

                location.href=data;
            } 

        });
    }


    function enterkey(category) {
        if (window.event.keyCode == 13) {
            switch(category) {
                case 1: {
                    studentLogIn();
                    break;
                }
                case 2 : {
                    parentLogIn();
                    break;
                } 
                case 3 : {
                    teacherLogIn();
                    break;
                }
            }
        }
    }

    function studentLogIn() {
        var id = $('#sID').val();
        var password = $('#sPassword').val();
        var rememberMe = $('#rememberMe').is(":checked");

            $.ajax({

                url: "/Action/auth/sProcess",

                type: "post", 

                data: {
                    id : id,
                    password : password,
                    rememberMe : rememberMe
                },
                success: function(data){
                    var parseData = JSON.parse(data.trim());

                    if(parseData.rememberMeChecked) {
                        localStorage.setItem("is", parseData.is);
                        localStorage.setItem("authKey", parseData.key);
                        localStorage.setItem("token", parseData.token);
                        localStorage.setItem("id", parseData.id);
                        localStorage.setItem("rememberMe", true);
                    }

                    if(!parseData.state) alert("로그인 정보를 다시 확인하세요");
                    else if(parseData.dup) {
                        if(confirm("다른 브라우저 기기에 자동로그인기록이 존재합니다. 현재 기기로 자동로그인 처리할까요?")){
                            location.href="/Manage/student/student";
                        }
                    }
                    else location.href="/Manage/student/student";

                } 

            });
    }

    function parentLogIn() {
        var id = $('#pID').val();
        var password = $('#pPassword').val();

            $.ajax({

                url: "/Action/auth/pProcess",

                type: "post", 

                data: {
                    id : id,
                    password : password
                },
                success: function(data){

                    if(data==-1) alert("로그인 정보를 다시 확인하세요");
                    else location.href=data;
                    

                } 

            });
    }

    function teacherLogIn() {
        var tcID = $('#tcID').val();
        var birthday = $('#password').val();
        var rememberMe = $('#rememberMe').is(":checked");
        
        //if(workerID=="01027602521"){
            $.ajax({

                url: "/Action/auth/tcProcess",

                type: "post", 

                data: {
                    id : tcID,
                    password : birthday,
                    rememberMe : rememberMe
                },
                success: function(data){

                    var parseData = JSON.parse(data.trim());
                    //console.log(parseData);

                    if(parseData.rememberMeChecked) {
                        localStorage.setItem("is", parseData.is);
                        localStorage.setItem("authKey", parseData.key);
                        localStorage.setItem("token", parseData.token);
                        localStorage.setItem("id", parseData.id);
                        localStorage.setItem("rememberMe", true);
                        //console.log(localStorage.getItem("authKey"));
                    }

                    if(parseData.is=='master') {
                        if(parseData.dup) {
                            if(confirm("다른 브라우저 기기에 자동로그인기록이 존재합니다. 현재 기기로 자동로그인 처리할까요?")){
                                location.href="/Manage/master/master";
                            }
                        }else{
                            location.href="/Manage/master/master";
                        }
                    }
                    else if(parseData.is=='teacher') {
                        if(parseData.dup) {
                            if(confirm("다른 브라우저 기기에 자동로그인기록이 존재합니다. 현재 기기로 자동로그인 처리할까요?")){
                                location.href="/Manage/teacher/teacher/index/"+parseData.name+"/"+parseData.classification;
                            }
                        }else{
                            location.href="/Manage/teacher/teacher/index/"+parseData.name+"/"+parseData.classification;
                        }
                    }
                    else alert("로그인 정보를 다시 확인하세요");

                }

            });
     
    }

</script>


<div style="margin-top:150PX;">
    <!----------로고 이미지-------------->
    <div class="row justify-content-center strongKorean" style="font-size:50px;"> 
        JO
    </div>
    <div class="row justify-content-center strongKorean" style="font-size:50px;padding-top:10px;padding-bottom:10px;"> 
        CORPORATION
    </div>

    <ul class="nav nav-pills mb-3 justify-content-center betweenMoblieLine" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-student-tab" data-toggle="pill" href="#pills-student" role="tab" aria-controls="pills-student" aria-selected="true">학생</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-parent-tab" data-toggle="pill" href="#pills-parent" role="tab" aria-controls="pills-parent" aria-selected="false">학부모</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-worker-tab" data-toggle="pill" href="#pills-worker" role="tab" aria-controls="pills-worker" aria-selected="false">관계자</a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">

        <div class="tab-pane fade show active" id="pills-student" role="tabpanel" aria-labelledby="pills-student-tab">
            <div class="row justify-content-center" style="margin-top:20px;">
                <input style="width:80%" type="number" maxlength="8" id="sID" name="sID" placeholder="학생번호">
            </div>
            <div class="row justify-content-center ">
                <small class="form-text text-muted">학생번호는 010을 제외한 전화번호 8글자 입니다</small>
            </div>
            <div class="row justify-content-center" style="margin-top:20px;">
                <input style="width:80%" type="password" id="sPassword"  onkeyup="enterkey(1);" placeholder="비밀번호">
            </div>
            <div class="row justify-content-center ">
                <small class="form-text text-muted">비밀번호는 생년월일 6글자 입니다</small>
            </div>
            <div class="row justify-content-center" style="margin-top:20px;">
                <p class="strongKorean cursorToPointer" style="font-size:25px;" onClick="studentLogIn()">로그인</p>
            </div> 
        </div>

        <div class="tab-pane fade" id="pills-parent" role="tabpanel" aria-labelledby="pills-parent-tab">
            <div class="row justify-content-center" style="margin-top:20px;">
                <input style="width:80%" type="number" maxlength="8" id="pID" name="pID" placeholder="학부모 번호">
            </div>
            <div class="row justify-content-center ">
                <small class="form-text text-muted">학부모번호는 010을 제외한 전화번호 8글자 입니다</small>
            </div>
            <div class="row justify-content-center" style="margin-top:20px;">
                <input style="width:80%" type="password" maxlength="6" id="pPassword"  onkeyup="enterkey(2);" placeholder="비밀번호">
            </div>
            <div class="row justify-content-center ">
                <small class="form-text text-muted">비밀번호는 생년월일 6글자 입니다</small>
            </div>
            <div class="row justify-content-center" style="margin-top:20px;">
                <p class="strongKorean cursorToPointer" style="font-size:25px;" onClick="parentLogIn()">로그인</p>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-worker" role="tabpanel" aria-labelledby="pills-worker-tab">
            <div class="row justify-content-center" style="margin-top:20px;">
                <!--input type="text" name="id" style="width:80%"
                <?php //echo "value='".$this->session->flashdata('tempID')."'";  ?> 
                    id="registerNumber" aria-describedby="emailHelp" placeholder="등록번호"-->
                <input style="width:80%" type="number" maxlength="8" id="tcID" name="tcID" placeholder="동록번호"/>
            </div> 
            <div class="row justify-content-center ">
                <small class="form-text text-muted">등록번호는 010을 제외한 전화번호 8글자 입니다</small>
            </div>
            <div class="row justify-content-center ">
                <input  style="width:80%" type="password" name="password" id="password" placeholder="비밀번호" onkeyup="enterkey(3);">
            </div>
            <div class="row justify-content-center ">
                <small id="passwordHelp" class="form-text text-muted">초기비밀번호는 개인의생년월일 6글자 입니다</small>
            </div>
            <!--div class="row justify-content-center" style="margin-top:20px;">
                <button type="button" class="btn btn-primary" onClick="movePageTo('/Action/auth/findRegisterNumber')">
                    등록번호 찾기
                </button>
                <button type="btton" class="btn btn-primary" onClick="movePageTo('/Action/auth/resetPassword')">비밀번호 찾기</button>
            </div-->
            <div class="row justify-content-center" style="margin-top:20px;">
                <p class="strongKorean cursorToPointer" style="font-size:25px;" onClick="teacherLogIn()">로그인</p>
            </div> 
        </div>
        <div class="row justify-content-center text-center form-check">
                <input class="form-check-input position-static" style="width:20px;height:20px;" type="checkbox" id="rememberMe" value="option1">로그인 유지하기
        </div>

    </div>

    

</div> 