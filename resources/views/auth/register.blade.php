@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-[calc(100vh-12rem)] flex items-center justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <!-- Header -->
        <div class="text-center mb-4 sm:mb-8">
            <a href="{{ route('home') }}" class="inline-block mb-3 sm:mb-4">
                <img src="{{ asset('images/lg.jpeg') }}" alt="Monana Platform" class="h-10 sm:h-12 w-auto mx-auto">
            </a>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white mb-2 sm:mb-3">
                Jisajili Sasa
            </h2>
            <p class="text-sm sm:text-base text-[#a0a0a0]">
                Tunaanza mazungumzo mafupi! 📝
            </p>
        </div>

        <!-- Chatbot Container -->
        <div class="card rounded-2xl sm:rounded-3xl shadow-2xl p-4 sm:p-6 md:p-8 relative overflow-hidden">
            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-100 dark:bg-blue-900 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 dark:bg-blue-900 rounded-full blur-3xl opacity-20 -ml-32 -mb-32"></div>
            
            <!-- Chat Messages Container -->
            <div id="chatContainer" class="space-y-4 sm:space-y-6 mb-4 sm:mb-6 max-h-[400px] sm:max-h-[500px] overflow-y-auto pr-2 relative z-10">
                <!-- Initial Bot Message -->
                <div class="flex items-start space-x-2 sm:space-x-3 message-item animate-slide-in">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white text-lg sm:text-xl font-bold shadow-lg">
                        🤖
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl sm:rounded-2xl rounded-tl-sm p-3 sm:p-4 shadow-md">
                            <p class="text-white text-sm sm:text-base leading-relaxed" id="initialMessage">
                                Habari! Karibu Monana Platform! 🍽️<br>Jina lako nani?
                            </p>
                        </div>
                        <p class="text-xs text-[#6b6b6b] mt-1 ml-1">Monana Bot</p>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="relative z-10" id="inputArea">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="flex-1 relative">
                        <input 
                            type="text" 
                            id="userInput" 
                            autocomplete="off"
                            placeholder="Andika hapa..."
                            class="w-full px-4 sm:px-5 py-3 sm:py-4 bg-[#2d2d2d] border border-[#333] rounded-xl sm:rounded-2xl text-white placeholder-[#6b6b6b] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base"
                        >
                        <div id="inputLoader" class="hidden absolute right-3 sm:right-4 top-1/2 -translate-y-1/2">
                            <div class="animate-spin rounded-full h-4 w-4 sm:h-5 sm:w-5 border-2 border-blue-500 border-t-transparent"></div>
                        </div>
                    </div>
                    <button 
                        id="sendButton"
                        class="flex-shrink-0 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white w-12 h-12 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl flex items-center justify-center transition-all transform hover:scale-110 active:scale-95 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                    >
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Skip Button (for optional fields) -->
                <button 
                    id="skipButton"
                    class="hidden mt-2 sm:mt-3 text-xs sm:text-sm text-[#a0a0a0] hover:text-blue-400 transition-colors"
                >
                    Ruka hili (Si lazima) →
                </button>
            </div>

            <!-- Progress Indicator -->
            <div class="mt-4 sm:mt-6 relative z-10">
                <div class="flex items-center justify-center space-x-1.5 sm:space-x-2">
                    <div id="progressStep1" class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-blue-600 transition-all duration-300"></div>
                    <div id="progressStep2" class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#333] transition-all duration-300"></div>
                    <div id="progressStep3" class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#333] transition-all duration-300"></div>
                    <div id="progressStep4" class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#333] transition-all duration-300"></div>
                    <div id="progressStep5" class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#333] transition-all duration-300"></div>
                    <div id="progressStep6" class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-[#333] transition-all duration-300"></div>
                </div>
                <p class="text-center text-xs text-[#6b6b6b] mt-2">
                    Hatua <span id="currentStep">1</span> ya <span id="totalSteps">6</span>
                </p>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="mt-4 sm:mt-6 text-center space-y-2">
            <p class="text-sm text-[#a0a0a0]">
                Tayari una akaunti?
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-semibold ml-1 transition-colors">
                    Ingia hapa
                </a>
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center text-xs sm:text-sm text-[#a0a0a0] hover:text-white transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Rudi nyumbani
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes slide-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in {
        animation: slide-in 0.5s ease-out;
    }

    .message-item {
        animation: slide-in 0.5s ease-out;
    }

    /* Custom Scrollbar */
    #chatContainer::-webkit-scrollbar {
        width: 6px;
    }

    #chatContainer::-webkit-scrollbar-track {
        background: transparent;
    }

    #chatContainer::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    #chatContainer::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    #chatContainer::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 3px;
    }

    #chatContainer::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
</style>

<script>
    let currentStep = 1;
    let registrationData = {};

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Elements
    const chatContainer = document.getElementById('chatContainer');
    const userInput = document.getElementById('userInput');
    const sendButton = document.getElementById('sendButton');
    const skipButton = document.getElementById('skipButton');
    const inputLoader = document.getElementById('inputLoader');
    const currentStepElement = document.getElementById('currentStep');

    // Focus input on load
    userInput.focus();

    // Handle Enter key
    userInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !sendButton.disabled) {
            handleSubmit();
        }
    });

    // Handle send button click
    sendButton.addEventListener('click', handleSubmit);

    // Handle skip button
    skipButton.addEventListener('click', handleSkip);

    function handleSubmit() {
        const answer = userInput.value.trim();
        
        if (!answer && currentStep <= 4) {
            return;
        }

        if (currentStep === 5 || currentStep === 6) {
            if (!answer) {
                showError('Tafadhali weka password.');
                return;
            }
        }

        // Disable input and show loader
        setLoading(true);

        // Add user message to chat
        addMessage(answer, 'user');

        // Clear input
        userInput.value = '';

        // Send to server
        fetch('{{ route("register.step") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ answer: answer })
        })
        .then(response => response.json())
        .then(data => {
            setLoading(false);
            
            if (data.success) {
                if (data.completed) {
                    // Registration completed
                    addMessage(data.message, 'bot', true);
                    
                    // Update progress
                    updateProgress(6);
                    
                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    // Next step
                    currentStep = data.step;
                    updateProgress(currentStep);
                    
                    // Change input type for password fields
                    if (currentStep === 5 || currentStep === 6) {
                        userInput.type = 'password';
                    } else {
                        userInput.type = 'text';
                    }
                    
                    // Show skip button for optional steps
                    if (data.isOptional) {
                        skipButton.classList.remove('hidden');
                    } else {
                        skipButton.classList.add('hidden');
                    }
                    
                    // Add bot response
                    setTimeout(() => {
                        addMessage(data.message, 'bot');
                        userInput.focus();
                    }, 300);
                }
            } else {
                // Show error
                showError(data.message || 'Hitilafu imetokea. Tafadhali jaribu tena.');
                userInput.focus();
            }
        })
        .catch(error => {
            setLoading(false);
            console.error('Error:', error);
            showError('Hitilafu imetokea. Tafadhali jaribu tena.');
            userInput.focus();
        });
    }

    function handleSkip() {
        setLoading(true);

        fetch('{{ route("register.skip") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            setLoading(false);
            
            if (data.success) {
                currentStep = data.step;
                updateProgress(currentStep);
                
                // Change input type for password fields
                if (currentStep === 5 || currentStep === 6) {
                    userInput.type = 'password';
                } else {
                    userInput.type = 'text';
                }
                
                if (data.isOptional) {
                    skipButton.classList.remove('hidden');
                } else {
                    skipButton.classList.add('hidden');
                }
                
                addMessage('✓ Ruka (Sijajibu)', 'user');
                
                setTimeout(() => {
                    addMessage(data.message, 'bot');
                    userInput.focus();
                }, 300);
            }
        })
        .catch(error => {
            setLoading(false);
            console.error('Error:', error);
            showError('Hitilafu imetokea. Tafadhali jaribu tena.');
        });
    }

    function addMessage(text, type, isHtml = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-3 message-item';
        
        if (type === 'user') {
            messageDiv.innerHTML = `
                <div class="flex-1 flex justify-end">
                    <div class="max-w-[80%]">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl sm:rounded-2xl rounded-tr-sm p-3 sm:p-4 shadow-md ml-auto">
                            <p class="text-white text-sm sm:text-base leading-relaxed">${escapeHtml(text)}</p>
                        </div>
                        <p class="text-xs text-[#6b6b6b] mt-1 mr-1 text-right">Wewe</p>
                    </div>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white text-lg sm:text-xl font-bold shadow-lg">
                    🤖
                </div>
                <div class="flex-1 min-w-0">
                    <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl sm:rounded-2xl rounded-tl-sm p-3 sm:p-4 shadow-md">
                        <p class="text-white text-sm sm:text-base leading-relaxed">${isHtml ? text : escapeHtml(text).replace(/\n/g, '<br>')}</p>
                    </div>
                    <p class="text-xs text-[#6b6b6b] mt-1 ml-1">Monana Bot</p>
                </div>
            `;
        }
        
        chatContainer.appendChild(messageDiv);
        
        // Scroll to bottom
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 message-item';
        errorDiv.innerHTML = `<p class="text-xs sm:text-sm">${escapeHtml(message)}</p>`;
        chatContainer.appendChild(errorDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        // Remove error after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    function setLoading(isLoading) {
        sendButton.disabled = isLoading;
        userInput.disabled = isLoading;
        skipButton.disabled = isLoading;
        
        if (isLoading) {
            inputLoader.classList.remove('hidden');
            sendButton.classList.add('opacity-50');
        } else {
            inputLoader.classList.add('hidden');
            sendButton.classList.remove('opacity-50');
        }
    }

    function updateProgress(step) {
        currentStepElement.textContent = step;
        
        for (let i = 1; i <= 6; i++) {
            const stepElement = document.getElementById(`progressStep${i}`);
            if (i <= step) {
                stepElement.classList.remove('bg-[#333]');
                stepElement.classList.add('bg-blue-600');
            } else {
                stepElement.classList.remove('bg-blue-600');
                stepElement.classList.add('bg-[#333]');
            }
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Auto-focus input
    userInput.addEventListener('blur', function() {
        if (!sendButton.disabled) {
            setTimeout(() => userInput.focus(), 100);
        }
    });
</script>
@endsection
