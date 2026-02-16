<div class="fixed bottom-4 md:bottom-6 right-4 md:right-6 z-50 flex flex-col items-end space-y-4 font-sans text-gray-800">

    <!-- Chat Box -->
    <div id="chat-box"
        class="hidden w-[calc(100vw-2rem)] max-w-md md:w-96 bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col h-[500px] md:h-[550px] backdrop-blur-sm">

        <!-- Header -->
        <div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 p-4 flex justify-between items-center relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
            
            <div class="flex items-center gap-3 relative z-10">
                <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl border border-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="w-5 h-5 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.159 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-white font-bold text-sm">Barangay Help Desk</h4>
                    <p class="text-white/90 text-xs flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse shadow-lg shadow-green-400/50"></span> 
                        <span class="font-medium">Online</span>
                    </p>
                </div>
            </div>

            <button onclick="window.toggleChat()" class="text-white/80 hover:text-white transition-colors duration-200 cursor-pointer relative z-10 p-1 hover:bg-white/10 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" class="flex-1 bg-gradient-to-b from-slate-50 to-white p-4 overflow-y-auto space-y-3">
            <div class="flex justify-start">
                <div class="bg-white text-slate-800 p-3.5 rounded-2xl rounded-tl-sm text-sm max-w-[85%] shadow-sm border border-slate-100">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span class="leading-relaxed">Welcome to <strong class="text-[#0052CC]">Barangay Daang Bakal</strong>! 👋<br>You can click a question below or type your message.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-slate-200 relative">
            <div class="relative">
                <input type="text" id="user-input" placeholder="Type your message..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-4 pr-12 py-3 text-sm focus:ring-2 focus:ring-[#0052CC] focus:border-[#0052CC] outline-none text-slate-800 transition-all duration-200 hover:bg-slate-100">
                <button onclick="window.sendMessage()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-gradient-to-r from-[#0052CC] to-[#1565C0] text-white p-2 rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Question List -->
        <div id="question-list" class="p-3 bg-gradient-to-b from-white to-slate-50 max-h-40 overflow-y-auto border-t border-slate-200 space-y-2"></div>
    </div>

    <!-- Chat Button -->
    <button onclick="window.toggleChat()"
        class="bg-gradient-to-r from-[#0052CC] to-[#1565C0] text-white p-4 rounded-2xl shadow-2xl hover:shadow-blue-500/50 transition-all duration-300 hover:scale-110 group relative cursor-pointer border-2 border-white/20">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
            class="w-6 h-6 md:w-7 md:h-7 text-black">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.159 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
        </svg>

        <span
            class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-white text-slate-800 text-xs font-bold px-4 py-2 rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-200 whitespace-nowrap shadow-xl pointer-events-none border border-slate-200">
            Need Help? 💬
        </span>
        
        <!-- Notification badge -->
        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
    </button>

</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {


        // 1. DATA
        const faqData = [
            { q: "How do I register?", a: "Residents can register online by filling out the registration form. If you experience any difficulty using the system, you may visit the barangay office, where the staff will assist you with the registration process and just bring valid id for verification." },
            { q: "What documents can I request?", a: "Barangay Clearance, Barangay Certificate, Indigency Clearance, and Resident Certificate." },
            { q: "How do I request documents?", a: "Once registered, log in to your account and go to the Homepage. Click the Request Document button, choose the type of document you need, fill out the required details, and submit your request for processing." },
            { q: "How long is the processing time?", a: "After submitting your request, the processing time is typically within 24 hours. You will receive a notification or email once your document is ready for pickup at the barangay office." },
            { q: "What should I bring when claiming my document?", a: "Please bring a valid ID or provide your reference code number as proof of tracking number when claiming your document." },
            { q: "How can I file a complaint?", a: "Log in to your account, navigate to the Homepage, and click the Submit Complaint button. You can then write and submit your complaint directly through the system." },
            { q: "What are your office hours?", a: "The barangay office is open from Monday to Friday, 7:00 AM to 5:00 PM, excluding holidays. For online transactions, the system is accessible 24/7 for residents’ convenience." },
            { q: "What are the curfew hours?", a: "The official curfew hours are from 10:00 PM to 4:00 AM, unless otherwise adjusted by barangay announcements or local ordinances. Residents are encouraged to stay indoors during these hours for safety and community order." },
        ];


        const questionList = document.getElementById('question-list');
        faqData.forEach(function (item) {
            let btn = document.createElement('button');
            btn.className = "w-full text-left text-xs bg-white hover:bg-gradient-to-r hover:from-blue-50 hover:to-slate-50 border border-slate-200 p-3 rounded-xl hover:border-blue-300 hover:shadow-md transition-all duration-200 text-slate-700 font-medium cursor-pointer group";
            
            const wrapper = document.createElement('div');
            wrapper.className = "flex items-center gap-2";
            
            const icon = document.createElement('span');
            icon.className = "text-blue-600 group-hover:scale-110 transition-transform duration-200";
            icon.innerHTML = `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>`;
            
            const text = document.createElement('span');
            text.innerText = item.q;
            
            wrapper.appendChild(icon);
            wrapper.appendChild(text);
            btn.appendChild(wrapper);


            // When button clicked
            btn.addEventListener('click', function () {
                window.appendMessage(item.q, 'user');
                setTimeout(function () { window.appendMessage(item.a, 'bot'); }, 500);
            });


            questionList.appendChild(btn);
        });


            //LISTEN FOR ENTER KEY
        const inputField = document.getElementById('user-input');
        inputField.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                window.sendMessage();
            }
        });


            //ANIMATION STYLE
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }
        `;
        document.head.appendChild(style);
    });
    // Toggle Chat Box
    window.toggleChat = function () {
        const box = document.getElementById('chat-box');
        if (box.classList.contains('hidden')) {
            box.classList.remove('hidden');
        } else {
            box.classList.add('hidden');
        }
    }
    // Send Message Logic
    window.sendMessage = function () {
        const input = document.getElementById('user-input');
        const text = input.value.trim();
        if (text === "") return;


        window.appendMessage(text, 'user');
        input.value = '';


        setTimeout(function () {
            const response = window.getBotResponse(text);
            window.appendMessage(response, 'bot');
        }, 600);
    }
    // Append Message to UI
    window.appendMessage = function (text, sender) {
        const chatArea = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.className = sender === 'user' ? "flex justify-end animate-fade-in-up" : "flex justify-start animate-fade-in-up";


        const bubble = document.createElement('div');
        bubble.className = sender === 'user'
            ? "bg-gradient-to-r from-[#0052CC] to-[#1565C0] text-black p-3.5 rounded-2xl rounded-tr-sm text-sm max-w-[85%] shadow-md"
            : "bg-white text-slate-800 p-3.5 rounded-2xl rounded-tl-sm text-sm max-w-[85%] shadow-sm border border-slate-100";


        bubble.innerHTML = text;
        div.appendChild(bubble);
        chatArea.appendChild(div);
        chatArea.scrollTop = chatArea.scrollHeight;
    }
    // Bot Logic
    window.getBotResponse = function (input) {
        input = input.toLowerCase();
        if (input.includes('hello') || input.includes('hi') || input.includes('hey')) return "Hello! How can I help you?";
        if (input.includes('time') || input.includes('open') || input.includes('hour')) return "We are open Mon-Fri, 7AM to 5PM.";
        if (input.includes('complaint')) return "You can file a complaint on your homepage.";
        if (input.includes('thanks') || input.includes('okay') || input.includes('bye')) return "You are welcome!";
        if (input.includes('register') || input.includes('account') || input.includes('sign up')) return "Visit the barangay hall or sign up online.";
        if (input.includes('document') || input.includes('clearance')) return "Request documents via your user dashboard.";


        return "I am an automated system. Please select a question from the list.";
    }
</script>



