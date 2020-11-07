<script>

    function gotoLogIn(num) {
        location.href="/Action/auth/index/"+num; 
    } 

    function findRegisterNumber() {
        var name = $('#tName').val();
        var phone = $('#tPhone').val();

            $.ajax({

                url: "/Action/auth/findRegisterNumber",

                type: "post", 

                data: {
                    name : name,
                    phone : phone
                },
                success: function(data){
                    
                    if(data==0){
                        $('#findResult').html('<span style="color:red;">등록된 사용자가 아닙니다.</span>');
                    }else{
                        var temp = '<span>등록된 번호는 '+data+' 입니다.</span>';
                        temp += '<br><a class="button button-border sc-btn-07 style-03 button-reset" onClick="gotoLogIn('+data+')">로그인하기</a>';
                        $('#findResult').html(temp); 
                    }

                } 

            });
    }

</script>


 <!--404 area start-->
 <div class="area-404 ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="content-404 text-center">
                    <div class="text-404">
                        <h1 class="korean-font">등록번호찾기</h1>
                        <div class="row justify-content-center">
                            <input type="text" id="tName" placeholder="이름">
                        </div>
                        <div class="row justify-content-center">
                            <input type="text" id="tPhone" placeholder="전화번호(-없이)">
                        </div> 
                        <a class="button button-border sc-btn-07 style-03 button-reset" onClick="findRegisterNumber()">찾기</a>
                        <div style="padding-top:50px;">
                            <div class="row" >
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="col-lg-6 offset-lg-3 text-center">
                                        <div id="findResult"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<!--404 area end-->
 