<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Scan Harga</title>

<style>
body { background:#0f172a; color:white; font-family:sans-serif; margin:0; }
.container { display:flex; height:100vh; }
.left { flex:2; padding:20px; }
.right { flex:1; background:#020617; padding:20px; }
h1 { font-size:60px; }
.price { font-size:80px; color:#22c55e; }
.error { color:red; font-size:40px; }
li { font-size:24px; margin:10px 0; }
.total { font-size:50px; margin-top:20px; }
input { opacity:0; position:absolute; }
</style>
</head>

<body>

<div class="container">
<div class="left">
    <button onclick="clearCart()" style="margin-top:20px;padding:10px 20px;font-size:18px;">
    Clear Semua
</button>
<h1 id="nama">Scan Barang</h1>
<div class="price" id="harga">Rp 0</div>
<div class="error" id="error"></div>
</div>

<div class="right">
<h2>List</h2>
<ul id="list"></ul>

<div class="total">Total: Rp <span id="total">0</span></div>
</div>
</div>

<input id="barcode" autofocus>

<script>
let cart = {};
let total = 0;

document.documentElement.requestFullscreen();

document.getElementById('barcode').addEventListener('change', function () {

    let code = this.value; // ✅ SIMPAN DULU

    fetch('/scan', {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body:JSON.stringify({ barcode: code })
    })
    .then(res=>res.json())
    .then(data=>{

        document.getElementById('error').innerText = '';

        if(data.status==='ok'){

            document.getElementById('nama').innerText = data.nama;
            document.getElementById('harga').innerText = 'Rp '+data.harga;

            // 🔥 PAKAI CODE (bukan this.value)
            if(cart[code]){
                cart[code].qty++;
            }else{
                cart[code]={
                barcode: code,
                nama:data.nama,
                harga:data.harga,
                qty:1
            };
            }

            render();

        }else{
            document.getElementById('error').innerText='Barang tidak ditemukan';
        }

    });

    this.value=''; // kosongkan setelah dipakai
});

let timer;

function resetTimer() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        location.reload();
    }, 60000); // 1 menit
}

function removeItem(barcode) {
    delete cart[barcode];
    render();
}

function clearCart() {
    cart = {};
    render();

    document.getElementById('nama').innerText = 'Scan Barang';
    document.getElementById('harga').innerText = 'Rp 0';
}

function render(){
    let list=document.getElementById('list');
    list.innerHTML='';
    total=0;

    Object.values(cart).forEach(item=>{
        let subtotal=item.harga*item.qty;
        total+=subtotal;

        let li=document.createElement('li');
        li.innerHTML = `
    ${item.nama} x${item.qty} - Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}
    <button onclick="removeItem('${item.barcode}')" style="margin-left:10px;color:red;">X</button>
`;
        list.appendChild(li);
    });

    document.getElementById('total').innerText=total;
}

setInterval(()=>document.getElementById('barcode').focus(),1000);
</script>

</body>
</html>