<html>
<head>
    <title>Block Chain etherium example</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <noscript>You need to enable JavaScript to run this app.</noscript>
    <div id="root">
        <div class="App">
            <header class="App-header"><img src="logo.png" class="App-logo" alt="logo">
                <h1 class="App-title">Welcome to Login with MetaMask Demo</h1>
            </header>
            <div id="enable_metamask">
                    <p>Please Enable your metamask first.</p>
                    <button class="Login-button Login-mm" onClick="enableMetamask()">Enable MetaMask</button>
             </div>
            <div class="App-intro" id="login_div" style="display:none;">
                <div>
                    <p>Please select your login method.<br>For the purpose of this demo, only MetaMask login is implemented.</p>
                    <button class="Login-button Login-mm" onClick="loginMetamask()">Login with MetaMask</button>
                </div>
            </div>
            <div id="download_metamask" style="display:none;">
                <a href="https://metamask.io">
                    <img src="https://raw.githubusercontent.com/MetaMask/faq/master/images/download-metamask.png" alt="">
                </a>
            </div>
            <div id="account_detail" style="display:none;">
                <ul style="list-style:none;">
                    <li>User Account Address: <span id="userAddress"></span></li>
                    <li>User Account Balance: <span id="userBalance"></span></li>
                    <li><button onClick="logout()" style="background:#ccc666" class="Login-button">Logout</button></li>
                </ul>
            </div>
        </div>
    </div>
    

</body>
<script>
    var accountsData = [];
    var isMetamaskLocked = false;
    var isLoggedIn = localStorage.getItem('userLoggedIn') || false;
    if(isInstalled()){ 
        if(isLoggedIn){ 
            web3.eth.getAccounts(function(err, accounts){
                var publicAddress = accounts[0];
                if(typeof(publicAddress) !='undefined'){
                        document.getElementById('userAddress').innerHTML  = publicAddress;
                        web3.eth.getBalance(publicAddress,function(err,balance){ 
                            document.getElementById('userBalance').innerHTML  = balance.c.toString(10);
                        });
                }
                   
            });
                    document.getElementById('enable_metamask').style.display = 'none';
                    document.getElementById('login_div').style.display =(isLoggedIn) ? 'none': 'block';
                    document.getElementById('download_metamask').style.display = 'none';        
                    document.getElementById('account_detail').style.display = (isLoggedIn) ? 'block': 'none';
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
   function logout(){
       localStorage.removeItem('userLoggedIn');
       window.location.reload();
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
                                console.log('balance',balance);                                
                            document.getElementById('userBalance').innerHTML  = balance.c.toString(10);
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
</html>