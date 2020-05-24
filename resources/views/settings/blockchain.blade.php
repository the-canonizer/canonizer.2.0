@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">Profile</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success! </strong>{{ Session::get('success')}}    
</div>
@endif

<div class="right-whitePnl">
   <div class="row justify-content-between">
    <div class="col-sm-12 margin-btm-2">
        <div class="well">
            <ul class="nav prfl_ul">
                <li class=""><a class="" href="{{ route('settings')}}">Profile Info</a></li>
                <li class=""><a class="" href="{{ route('settings.sociallinks')}}">Social Oauth Verification</a></li>                
                <li><a class="" href="{{ route('settings.changepassword')}}">Change Password</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Nick Names</a></li>
                <li class=""><a class="" href="{{ route('settings.support')}}" >Supported Camps</a></li>
                <!-- <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li> -->
                <li class="active"><a class="" href="{{ route('settings.blockchain')}}">Crypto Verification (was Metamask Account)</a></li>
                
                
            </ul>
             
            <div id="myTabContent" class="add-nickname-section" style="margin-top:20px;">
                <div id="savedAddress">
               <table class="table">
               <thead class="thead-default">
                <tr>
                    <th>Sr</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>                                        
             </thead>
                @if(count($addresses) > 0)                   
               <tbody id="userAddresses">
                    @foreach($addresses as $key=>$addr)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$addr->name}}</td>
                            <td>{{$addr->address}}</td>
                            <td>{{$addr->balance}} ETH</td>
                            <td>
                                <button class="btn btn-xs btn-primary" onClick=editAddress('<?php echo $addr->address; ?>')>Edit</button>
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                  @else
                    <tbody id="userAddresses">
                        <tr>
                            <td colspan="2"> No data found</td>
                        </tr>
                    </tbody>
                  @endif
               </table>

             </div>
                 <div id="create_address" style="display:none;">
                    <h5>Update Address</h5>
                    <form action="{{ route('settings.save-ether-address')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-sm-6 margin-btm-1">
                            <label for="name">Name  <span style="color:red">*</span></label>
                            <input type="text"  name="name" class="form-control" id="name" value="{{ old('nick_name')}}">
                            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                        </div>
                        <div class="col-sm-6 margin-btm-1">
                            <label for="address">Address  <span style="color:red">*</span></label>
                            <input type="text" name="address" readonly class="form-control" id="address" value="{{ old('address')}}">
                            @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
                        </div> 
                        <div class="col-sm-6 margin-btm-1">
                            <label for="balance">Balance  <span style="color:red">*</span></label>
                            <input type="text" name="balance" readonly class="form-control" id="balance" value="{{ old('balance')}}">
                            @if ($errors->has('balance')) <p class="help-block">{{ $errors->first('balance') }}</p> @endif
                        </div>
                    </div>
                    
                    <button type="submit" id="submit_create" class="btn btn-login">Save</button>
                    <div  id="cencel_create" onClick="cancelCreate()"  style="cursor:pointer;" class="btn btn-login">Cancel</div>
                </form>
                 </div>
                     <div id="root" class="row">
                        <noscript>You need to enable JavaScript to run this app.</noscript>
                   
                        <div class="App" class="col-md-12">
                            <div id="enable_metamask">
                                    <p>Please Enable your metamask first.</p>
                                    <button class="btn btn-login" onClick="enableMetamask()">Enable MetaMask</button>
                             </div>
                            <div class="App-intro" id="login_div" style="display:none;">
                                <div>
                                    <p>Please login with metamask credentials to get the details.</p>
                                    <button class="btn btn-login" onClick="loginMetamask()">Login with MetaMask</button>
                                </div>
                            </div>
                            <div id="download_metamask" style="display:none;">
                                <a href="https://metamask.io">
                                    <img src="https://raw.githubusercontent.com/MetaMask/faq/master/images/download-metamask.png" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
        </div>
    </div>   
 </div></div>
</div>  <!-- /.right-whitePnl-->

    <script>
    var accountsData = [];
    var isMetamaskLocked = false;
    var isLoggedIn = localStorage.getItem('userLoggedIn') || false;
    var dbAddresses ={};
    var addressLenth = <?php echo count($addresses); ?>;
     <?php 
     $i = 0;
     if(count($addresses) > 0){
        
        foreach($addresses as $k => $addr){
                $i = $i+1;
             ?>
            dbAddresses['<?php echo $addr->address; ?>'] = {};
            var data = {
                name:'<?php echo $addr->name; ?>',
                address: '<?php echo $addr->address; ?>',
                balance: '<?php echo $addr->balance; ?>'
            }
            dbAddresses['<?php echo $addr->address; ?>'] = data;
       <?php }
     }?>
     var key = <?php echo $i; ?>;
    if(isInstalled()){ 
        if(isLoggedIn){ 
            web3.eth.getAccounts(function(err, accounts){
                if(err != null){ 
                    localStorage.removeItem('userLoggedIn');
                    isMetamaskLocked = true;
                    isLoggedIn = false;
                    $('#enable_metamask').show();
                    $('#login_div').hide();
                    $('#download_metamask').hide();                     
                    $('#savedAddress').hide();
                }
                if(accounts.length == 0){ 
                    localStorage.removeItem('userLoggedIn');
                    isMetamaskLocked = true;
                    isLoggedIn = false;
                    $('#enable_metamask').show();
                    $('#login_div').hide();
                    $('#download_metamask').hide();                     
                    $('#savedAddress').hide();
                }
                var publicAddress = accounts[0];
                if(typeof(publicAddress) !='undefined'){
                        if(typeof(dbAddresses[publicAddress]) =='undefined') {
                             var html = "";
                             var sr = key + 1;
                             var name = ""; 
                             html = html + "<tr><td>"+sr+"</td><td>"+name+"</td><td>"+publicAddress+"</td>";
                             web3.eth.getBalance(publicAddress,function(err,balance){
                                var acBalance = web3.fromWei(balance, "ether")+"";
                                dbAddresses[publicAddress] = { name:'',address:publicAddress,balance:acBalance};
                                html = html + "<td>"+web3.fromWei(balance, "ether") + " ETH"+"</td>";
                                html = html + "<td><button class='btn btn-primary btn-xs' onClick=editAddress('"+publicAddress+"')>Edit</button></td></tr>";
                                if(addressLenth){
                                        $('#userAddresses').append(html);
                                }else{
                                    $('#userAddresses').html(html);
                                }   
                                
                            });
                        }
                     $('#savedAddress').show();
                   }
                if(!isMetamaskLocked){ 
                    $('#enable_metamask').hide();
                    (isLoggedIn) ? $('#login_div').hide(): $('#login_div').show();
                    $('#download_metamask').hide(); 
                    $('#savedAddress').show();
                }
                   
            });
            
            
        }else{ 
            isLocked().then(function(r){
                if(isMetamaskLocked){ 
                    $('#enable_metamask').show();
                    $('#login_div').hide();
                    $('#download_metamask').hide();                    
                    $('#savedAddress').hide();
                }else{
                    $('#enable_metamask').hide();
                    (isLoggedIn) ? $('#login_div').hide() : $('#login_div').show();
                    $('#download_metamask').hide(); 
                    $('#savedAddress').show();
                }
            })
            
        }
       
    }else{ 
        $('#login_div').hide();
        $('#enable_metamask').hide()
        $('#download_metamask').show();
        $('#savedAddress').hide();
        
    }
    //var web3 = window.Web3;
    async function enableEther(){
        return await ethereum.enable();
    }

    
    function enableMetamask(){
        enableEther().then(function(r){
                $('#enable_metamask').hide();
                 (isLoggedIn) ? $('#login_div').hide(): $('#login_div').show();
                $('#download_metamask').hide();
                $('#savedAddress').hide();
        })
    }
    
    function isInstalled() {
            if (typeof web3 !== 'undefined'){
                console.log('MetaMask is installed')
                ethereum.autoRefreshOnNetworkChange = false;
                return true;
            } 
            return false;
    }

    async function isLocked() {
            return new Promise((resolve,reject)=>{
                web3.eth.getAccounts(function(err, accounts){
                if (err != null) {
                    console.log(err)
                    isMetamaskLocked =  true;
                    resolve(true);
                }
                else if (accounts.length === 0) {
                    console.log('MetaMask is locked');
                    isMetamaskLocked =  true;
                    resolve(true);
                }
                else {
                    accountsData = accounts;
                    console.log('MetaMask is unlocked')
                    isMetamaskLocked = false;
                    resolve(false);
                }
            });
            
            });
    }
   function editAddress(address){
        $("#name").val(dbAddresses[address].name);
        $('#address').val(dbAddresses[address].address);
        $('#balance').val(dbAddresses[address].balance);
        $("#create_address").show();
   }

   function cancelCreate(){
         $("#name").val("");
        $('#address').val("");
        $('#balance').val("");
        $("#create_address").hide();
   }
   function loginMetamask() {
                var publicAddress = web3.eth.accounts[0];
                var msg = web3.sha3('0x879a053d4800c6354e76c7985a865d2922c82fb5b3f4577b2fe08b998954f2e0');             
                var params = [msg,publicAddress];
                var method = "personal_sign";
                web3.currentProvider.sendAsync({
                    method,
                    params,
                    publicAddress,
                },function(err,result){
                    web3.personal.ecRecover(msg, result.result, function(error, signing_address) {
                         if(signing_address == publicAddress){
                            localStorage.setItem('userLoggedIn',true);
                            var sr = key + 1;
                            var html =  "";
                            var name = typeof(dbAddresses[publicAddress])!='undefined' ? dbAddresses[publicAddress].name: '';
                           
                             if(typeof(dbAddresses[publicAddress]) =='undefined') {
                                html = html + "<tr><td>"+sr+"</td><td>"+name+"</td><td>"+publicAddress+"</td>";
                                web3.eth.getBalance(publicAddress,function(err,balance){
                                    var acBalance = web3.fromWei(balance, "ether")+"";
                                        dbAddresses[publicAddress] = { name:'',address:publicAddress,balance:acBalance};
                                    html = html + "<td>"+web3.fromWei(balance, "ether") + " ETH"+"</td>";
                                    html = html + "<td><button class='btn btn-primary btn-xs' onClick=editAddress('"+publicAddress+"')>Edit</button></td><</tr>";
                                     if(addressLenth){
                                        $('#userAddresses').append(html);
                                    }else{
                                        $('#userAddresses').html(html);
                                    }   
                                });
                             }
                             $('#savedAddress').show();                          
                            $('#enable_metamask').hide();
                            $('#login_div').hide();
                            $('#download_metamask').hide();
                        }   
                    })
                });
           
       
    }
    </script>


    @endsection

