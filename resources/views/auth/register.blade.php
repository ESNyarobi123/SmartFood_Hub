@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-[calc(100vh-12rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <div class="max-w-2xl w-full">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <h2 class="text-4xl sm:text-5xl font-bold text-blue-900 dark:text-blue-100 mb-3">
                Jisajili Sasa
            </h2>
            <p class="text-slate-600 dark:text-slate-400 text-lg">
                Tunaanza mazungumzo mafupi! 📝
            </p>
        </div>

        <!-- Chatbot Container -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-6 sm:p-8 relative overflow-hidden">
            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-100 dark:bg-blue-900 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 dark:bg-blue-900 rounded-full blur-3xl opacity-20 -ml-32 -mb-32"></div>
            
            <!-- Chat Messages Container -->
            <div id="chatContainer" class="space-y-6 mb-6 max-h-[500px] overflow-y-auto pr-2 relative z-10">
                <!-- Initial Bot Message -->
                <div class="flex items-start space-x-3 message-item animate-slide-in">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg">
                        🤖
                    </div>
                    <div class="flex-1">
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-2xl rounded-tl-sm p-4 shadow-md">
                            <p class="text-slate-800 dark:text-slate-200 text-base leading-relaxed" id="initialMessage">
                                Habari! Karibu SmartFood Hub! 🍽️<br>Jina lako nani?
                            </p>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-1">SmartFood Bot</p>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="relative z-10" id="inputArea">
                <div class="flex items-center space-x-3">
                    <div class="flex-1 relative">
                        <input 
                            type="text" 
                            id="userInput" 
                            autocomplete="off"
                            placeholder="Andika hapa..."
                            class="w-full px-5 py-4 border-2 border-slate-300 dark:border-slate-600 rounded-2xl bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-base placeholder:text-slate-400 dark:placeholder:text-slate-500"
                        >
                        <div id="inputLoader" class="hidden absolute right-4 top-1/2 -translate-y-1/2">
                            <div class="animate-spin rounded-full h-5 w-5 border-2 border-blue-500 border-t-transparent"></div>
                        </div>
                    </div>
                    <button 
                        id="sendButton"
                        class="flex-shrink-0 bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-2xl flex items-center justify-center transition-all transform hover:scale-110 active:scale-95 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Skip Button (for optional fields) -->
                <button 
                    id="skipButton"
                    class="hidden mt-3 text-sm text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                >
                    Ruka hili (Si lazima) →
                </button>
            </div>

            <!-- Progress Indicator -->
            <div class="mt-6 relative z-10">
                <div class="flex items-center justify-center space-x-2">
                    <div id="progressStep1" class="w-3 h-3 rounded-full bg-blue-600 transition-all duration-300"></div>
                    <div id="progressStep2" class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-300"></div>
                    <div id="progressStep3" class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-300"></div>
                    <div id="progressStep4" class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-300"></div>
                    <div id="progressStep5" class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-300"></div>
                    <div id="progressStep6" class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-300"></div>
                </div>
                <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-2">
                    Hatua <span id="currentStep">1</span> ya <span id="totalSteps">6</span>
                </p>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="mt-6 text-center space-y-2 animate-fade-in">
            <p class="text-slate-600 dark:text-slate-400">
                Tayari una akaunti?
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 font-semibold ml-1">
                    Ingia hapa
                </a>
            </p>
            <a href="{{ route('home') }}" class="inline-block text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 text-sm">
                ← Rudi nyumbani
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

    .dark #chatContainer::-webkit-scrollbar-thumb {
        background: #475569;
    }

    .dark #chatContainer::-webkit-scrollbar-thumb:hover {
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
                        <div class="bg-blue-600 text-white rounded-2xl rounded-tr-sm p-4 shadow-md ml-auto">
                            <p class="text-white text-base leading-relaxed">${escapeHtml(text)}</p>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 mr-1 text-right">Wewe</p>
                    </div>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg">
                    🤖
                </div>
                <div class="flex-1">
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-2xl rounded-tl-sm p-4 shadow-md">
                        <p class="text-slate-800 dark:text-slate-200 text-base leading-relaxed">${isHtml ? text : escapeHtml(text).replace(/\n/g, '<br>')}</p>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-1">SmartFood Bot</p>
                </div>
            `;
        }
        
        chatContainer.appendChild(messageDiv);
        
        // Scroll to bottom
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-4 message-item';
        errorDiv.innerHTML = `<p class="text-sm">${escapeHtml(message)}</p>`;
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
                stepElement.classList.remove('bg-slate-300', 'dark:bg-slate-600');
                stepElement.classList.add('bg-blue-600');
            } else {
                stepElement.classList.remove('bg-blue-600');
                stepElement.classList.add('bg-slate-300', 'dark:bg-slate-600');
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
