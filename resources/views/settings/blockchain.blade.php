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
                <li class=""><a class="" href="{{ route('settings')}}">Manage Profile Info</a></li>
                <li><a class="" href="{{ route('settings.nickname')}}" >Add & Manage Nick Names</a></li>
                <li class=""><a class="" href="{{ route('settings.support')}}" >My Supports</a></li>
                <li><a class="" href="{{ route('settings.algo-preferences')}}">Default Algorithm</a></li>
                <li><a class="" href="{{ route('settings.changepassword')}}">Change Password</a></li>
                <li class="active"><a class="" href="{{ route('settings.blockchain')}}">Metamask Account</a></li>
                
            </ul>

            <div id="myTabContent" class="add-nickname-section" style="margin-top:20px;">
                    <noscript>You need to enable JavaScript to run this app.</noscript>
                    <div id="root" class="row">
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
                            <div id="account_detail" style="display:none;">
                                <ul style="list-style:none;">
                                    <li><b>Account Address:</b> <span id="userAddress"></span></li>
                                    <li><b>Account Balance:</b> <span id="userBalance"></span></li>
                                </ul>
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
    if(isInstalled()){ 
        if(isLoggedIn){ 
            web3.eth.getAccounts(function(err, accounts){
                if(err != null){
                    localStorage.removeItem('userLoggedIn');
                    isMetamaskLocked = true;
                    isLoggedIn = false;
                    document.getElementById('enable_metamask').style.display ='block';
                    document.getElementById('login_div').style.display = 'none';
                    document.getElementById('download_metamask').style.display = 'none';        
                    document.getElementById('account_detail').style.display = 'none';
                }
                if(accounts.length == 0){
                    localStorage.removeItem('userLoggedIn');
                    isMetamaskLocked = true;
                    isLoggedIn = false;
                    document.getElementById('enable_metamask').style.display ='block';
                    document.getElementById('login_div').style.display = 'none';
                    document.getElementById('download_metamask').style.display = 'none';        
                    document.getElementById('account_detail').style.display = 'none';
                }
                var publicAddress = accounts[0];
                if(typeof(publicAddress) !='undefined'){
                        document.getElementById('userAddress').innerHTML  = publicAddress;
                        web3.eth.getBalance(publicAddress,function(err,balance){ 
                            document.getElementById('userBalance').innerHTML  = web3.fromWei(balance, "ether") + " ETH";
                        });
                }
                if(!isMetamaskLocked){
                    document.getElementById('enable_metamask').style.display = 'none';
                    document.getElementById('login_div').style.display =(isLoggedIn) ? 'none': 'block';
                    document.getElementById('download_metamask').style.display = 'none';        
                    document.getElementById('account_detail').style.display = (isLoggedIn) ? 'block': 'none';
                }
                   
            });
            
            
        }else{ 
            isLocked().then(function(r){
                if(isMetamaskLocked){
                    document.getElementById('enable_metamask').style.display ='block';
                    document.getElementById('login_div').style.display = 'none';
                    document.getElementById('download_metamask').style.display = 'none';        
                    document.getElementById('account_detail').style.display = 'none';
                }else{
                    document.getElementById('enable_metamask').style.display = 'none';
                    document.getElementById('login_div').style.display =(isLoggedIn) ? 'none': 'block';
                    document.getElementById('download_metamask').style.display = 'none';        
                    document.getElementById('account_detail').style.display = (isLoggedIn) ? 'block': 'none';
                }
            })
            
        }
       
    }else{
        document.getElementById('login_div').style.display = 'none';
        document.getElementById('enable_metamask').style.display = 'none';
        document.getElementById('account_detail').style.display = 'none';
        document.getElementById('download_metamask').style.display = 'block';
        
    }
    //var web3 = window.Web3;
    async function enableEther(){
        return await ethereum.enable();
    }

    
    function enableMetamask(){
        enableEther().then(function(r){
                document.getElementById('enable_metamask').style.display = 'none';
                document.getElementById('login_div').style.display =(isLoggedIn) ? 'none': 'block';
                document.getElementById('download_metamask').style.display = 'none';        
                document.getElementById('account_detail').style.display = (isLoggedIn) ? 'block': 'none';
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
                            document.getElementById('userAddress').innerHTML  = publicAddress;
                             web3.eth.getBalance(publicAddress,function(err,balance){
                                 document.getElementById('userBalance').innerHTML  = web3.fromWei(balance, "ether") + " ETH"
                            });
                            document.getElementById('enable_metamask').style.display = 'none';
                            document.getElementById('login_div').style.display = 'none';
                            document.getElementById('account_detail').style.display = 'block';
                            document.getElementById('download_metamask').style.display = 'none';
                        }   
                    })
                });
           
       
    }
    </script>


    @endsection

