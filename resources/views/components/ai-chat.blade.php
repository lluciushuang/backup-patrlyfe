<style>
     .ai-fab {
         position: fixed;
         bottom: 30px;
         right: 30px;
         width: 60px;
         height: 60px;
         background: linear-gradient(135deg, #E8521A, #b3390f);
         border-radius: 50%;
         display: flex; align-items: center; justify-content: center;
         cursor: pointer;
         box-shadow: 0 4px 15px rgba(232, 82, 26, 0.4);
         z-index: 9999;
         transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
     }
     .ai-fab:hover { transform: scale(1.15) rotate(-10deg); }
     
     .ai-modal-overlay {
         position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
         background: rgba(10, 10, 8, 0.85); backdrop-filter: blur(5px);
         z-index: 10000;
         display: none;
         align-items: center; justify-content: center;
     }
     .ai-modal-overlay.show { display: flex; animation: fadeIn 0.2s ease-out forwards; }
     @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
     
     .ai-modal-content {
         background: #111110; border: 1px solid rgba(232, 82, 26, 0.3);
         width: 90%; max-width: 500px; height: 65vh; border-radius: 8px;
         display: flex; flex-direction: column; overflow: hidden;
         box-shadow: 0 20px 50px rgba(0,0,0,0.8);
     }
     .ai-modal-header {
         padding: 1.2rem; background: #151513; border-bottom: 1px solid rgba(242,239,230,0.08);
         display: flex; justify-content: space-between; align-items: center;
     }
     .ai-modal-body {
         flex: 1; padding: 1.5rem; overflow-y: auto; background: #0c0c0b;
     }
     .ai-modal-footer {
         padding: 1rem; border-top: 1px solid rgba(242,239,230,0.08); display: flex; gap: 0.5rem; background: #111110;
     }
     .ai-modal-footer input {
         flex: 1; padding: 0.85rem; background: #1A1A16; border: 1px solid rgba(242,239,230,0.1);
         color: #F2EFE6; border-radius: 4px; outline: none; font-family: inherit;
     }
     .ai-modal-footer input:focus { border-color: #E8521A; }
     
     .ai-msg { margin-bottom: 1.2rem; padding: 0.85rem 1.2rem; border-radius: 8px; width: fit-content; max-width: 85%; font-size: 0.9rem; line-height: 1.5; color: #F2EFE6; }
     .ai-msg.bot { background: rgba(242,239,230,0.05); border-bottom-left-radius: 2px; border: 1px solid rgba(242,239,230,0.1); }
     .ai-msg.user { background: rgba(232, 82, 26, 0.15); margin-left: auto; border-bottom-right-radius: 2px; border: 1px solid rgba(232, 82, 26, 0.3); }

     .ai-product-card {
         display: flex;
         gap: 0.75rem;
         padding: 0.75rem;
         background: #151513;
         border: 1px solid rgba(242,239,230,0.08);
         border-radius: 6px;
         margin-top: 0.5rem;
         cursor: pointer;
         transition: all 0.2s ease;
     }
     .ai-product-card:hover {
         border-color: rgba(232, 82, 26, 0.4);
         background: rgba(232, 82, 26, 0.05);
     }
     .ai-product-image {
         width: 60px;
         height: 60px;
         background: #111110;
         border-radius: 4px;
         flex-shrink: 0;
         display: flex;
         align-items: center;
         justify-content: center;
         overflow: hidden;
     }
     .ai-product-image img {
         width: 100%;
         height: 100%;
         object-fit: contain;
     }
     .ai-product-info {
         flex: 1;
         min-width: 0;
     }
     .ai-product-name {
         font-weight: 600;
         color: #F2EFE6;
         font-size: 0.85rem;
         margin-bottom: 0.25rem;
         white-space: nowrap;
         overflow: hidden;
         text-overflow: ellipsis;
     }
     .ai-product-meta {
         font-size: 0.75rem;
         color: #888880;
         margin-bottom: 0.25rem;
     }
     .ai-product-price {
         font-family: var(--font-mono);
         font-size: 0.9rem;
         color: #E8521A;
         font-weight: 700;
     }
     .ai-stock-badge {
         display: inline-block;
         font-size: 0.65rem;
         padding: 0.15rem 0.5rem;
         border-radius: 2px;
         background: rgba(46, 204, 113, 0.15);
         color: #2ecc71;
         margin-left: 0.5rem;
     }
     .ai-stock-badge.out {
         background: rgba(231, 76, 60, 0.15);
         color: #e74c3c;
     }
</style>

<!-- Trigger Kanan Bawah -->
<div class="ai-fab" onclick="handleAiFabClick()">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
</div>

<script>
    function handleAiFabClick() {
        if (document.querySelector('meta[name="csrf-token"]')) {
            document.getElementById('aiModal').classList.add('show');
        } else {
            window.location.href = '{{ route('login') }}';
        }
    }
</script>

<!-- Modal -->
<div class="ai-modal-overlay" id="aiModal">
    <div class="ai-modal-content">
        <div class="ai-modal-header">
            <div style="font-weight:bold; color:#E8521A; letter-spacing: 0.05em; display:flex; align-items:center; gap:0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/><line x1="8" y1="16" x2="8" y2="16"/><line x1="16" y1="16" x2="16" y2="16"/></svg>
                Mekanik AI Pro
            </div>
            <button onclick="document.getElementById('aiModal').classList.remove('show')" style="background:transparent; border:none; color:#888880; cursor:pointer; font-size:1.5rem; transition:0.2s;">&times;</button>
        </div>
        
        <div class="ai-modal-body" id="aiChatBody">
            <div class="ai-msg bot">Halo! Saya Mekanik AI dari Partlyfe. Motormu lagi ada masalah apa nih? Silakan ketik keluhanmu di bawah ini.</div>
        </div>
        
        <div class="ai-modal-footer">
            <input type="text" id="aiInput" placeholder="Ketik keluhan motormu..." onkeypress="if(event.key === 'Enter') sendAiMsg()">
            <button onclick="sendAiMsg()" style="background:#E8521A; color:white; border:none; padding:0 1.5rem; border-radius:4px; cursor:pointer; font-weight:bold;">Kirim</button>
        </div>
    </div>
</div>

<script>
     function sendAiMsg() {
         let input = document.getElementById('aiInput');
         let val = input.value.trim();
         if(!val) return;
         
         let csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
         
         if (!csrfToken) {
             window.location.href = '{{ route('login') }}';
             return;
         }
         
         document.getElementById('aiChatBody').innerHTML += '<div class="ai-msg user">' + escapeHtml(val) + '</div>';
         input.value = '';
         
         const loadingId = 'ai-loading-' + Date.now();
         document.getElementById('aiChatBody').innerHTML += '<div id="' + loadingId + '" class="ai-msg bot">AI sedang mengetik...</div>';
         scrollToBottom();
         
         fetch("{{ route('customer.ai-chat.send') }}", {
             method: 'POST',
             headers: {
                 'Content-Type': 'application/json',
                 'Accept': 'application/json',
                 'X-CSRF-TOKEN': csrfToken,
                 'X-Requested-With': 'XMLHttpRequest'
             },
             body: JSON.stringify({ message: val })
         })
         .then(r => {
             return r.text().then(text => {
                 try {
                     return JSON.parse(text);
                 } catch (e) {
                     throw new Error(text.substring(0, 100));
                 }
             });
         })
         .then(data => {
             document.getElementById(loadingId).remove();
             if(data.status === 'success' || data.reply) {
                 let replyHtml = '<div class="ai-msg bot">' + data.reply + '</div>';
                 
                 if (data.products && data.products.length > 0) {
                     data.products.forEach(function(product) {
                         replyHtml += '<div class="ai-product-card" onclick="openProduct(' + product.id + ')">';
                         replyHtml += '<div class="ai-product-image">';
                         if (product.image) {
                             replyHtml += '<img src="' + product.image + '" alt="' + escapeHtml(product.name) + '">';
                         } else {
                             replyHtml += '<svg width="30" height="30" viewBox="0 0 100 100" fill="none" stroke="#F2EFE6" stroke-width="1.5"><rect x="15" y="15" width="70" height="70" rx="8"/></svg>';
                         }
                         replyHtml += '</div>';
                         replyHtml += '<div class="ai-product-info">';
                         replyHtml += '<div class="ai-product-name">' + product.name + '<span class="ai-stock-badge ' + (product.available ? '' : 'out') + '">' + (product.available ? 'Tersedia' : 'Habis') + '</span></div>';
                         replyHtml += '<div class="ai-product-meta">' + product.brand + ' · SKU: ' + product.sku + '</div>';
                         replyHtml += '<div class="ai-product-price">Rp ' + product.price + '</div>';
                         replyHtml += '</div>';
                         replyHtml += '</div>';
                     });
                 }
                 
                 document.getElementById('aiChatBody').innerHTML += replyHtml;
             } else {
                 document.getElementById('aiChatBody').innerHTML += '<div class="ai-msg bot">Gagal memproses pertanyaan. Coba lagi nanti.</div>';
             }
             scrollToBottom();
         })
         .catch(err => {
             document.getElementById(loadingId).remove();
             console.error('AI Chat Error:', err);
             document.getElementById('aiChatBody').innerHTML += '<div class="ai-msg bot">Error: ' + (err.message || err) + '</div>';
             scrollToBottom();
         });
     }
     
     function openProduct(id) {
         window.location.href = '/produk/' + id;
     }
     
     function scrollToBottom() {
         const body = document.getElementById('aiChatBody');
         body.scrollTop = body.scrollHeight;
     }
     
     function escapeHtml(text) {
         return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
     }
</script>