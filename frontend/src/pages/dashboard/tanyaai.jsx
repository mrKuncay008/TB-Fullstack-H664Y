import { useState } from 'react';
import { PostApi } from '@/api/geminiapi';
import Headerchat from '@/componens/headerchat';
import MarkdownIt from 'markdown-it';

export default function Tanyaai() {
  const [messages, setMessages] = useState([
    {
      sender: 'gemini',
      text: 'Hello saya Kasbot, Apa yang mau di tanyakan ?',
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
    },
  ]);
  const [input, setInput] = useState('');
  const [loading, setLoading] = useState(false);

  const md = new MarkdownIt(); 

  const handleSend = async () => {
    if (!input.trim()) return;

    setInput('')
    const newMessages = [
      ...messages,
      { sender: 'user', text: input, time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }) },
    ];
    setMessages(newMessages);


    setLoading(true);


    const response = await PostApi({ message: input });

    if (response.success) {
      setMessages((prev) => [
        ...prev,
        { sender: 'gemini', text: response.data.chat.geminiResponse, time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }) },
      ]);
    } else {
      const errorMessage = response.error === "The model is overloaded. Please try again later."
        ? "Halo maaf ya, Pengetahuan yang ingin saya sampaikan sedang overload kamu bisa refresh dan gunakan sebentar lagi karna sumber daya kami sangat terbatas."
        : response.error || 'Server error';

      setMessages((prev) => [
        ...prev,
        { sender: 'gemini', text: errorMessage, time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }) },
      ]);
    }

    setLoading(false);


  };

  const handleKeyDown = (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      handleSend();
      setInput('');
    }
  };

  return (
    <div className="flex h-screen antialiased text-gray-800">
      <div className="flex flex-row h-full w-full overflow-x-hidden">
        <div className="flex flex-col flex-auto h-full p-6">
          <Headerchat />
          <div className="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-full p-4">
            <div className="flex flex-col h-full overflow-x-auto mb-4">
              <div className="flex flex-col h-full">
                <div className="flex flex-col space-y-4">
                  {messages.map((msg, index) => (
                    <div key={index} className={`flex ${msg.sender === 'user' ? 'justify-end' : 'justify-start'}`}>
                      {msg.sender === 'gemini' && (
                        <div className="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0 mr-2">
                          <img className="w-10 h-10 rounded-full" src="/public/img/robot.jpg" alt="Gemini AI" />
                        </div>
                      )}
                      <div className={`relative text-sm my-3 ${msg.sender === 'user' ? 'bg-indigo-100' : 'bg-white'} py-2 px-4 shadow rounded-xl`}>
                        {/* Render teks dengan Markdown */}
                        <div dangerouslySetInnerHTML={{ __html: md.render(msg.text) }} />
                        <div className=" text-xs bottom-0 right-0 mt-2  mr-2 text-gray-500">{msg.time}</div>
                      </div>
                    </div>
                  ))}
                  {/* Menampilkan animasi loading saat menunggu respons */}
                  {loading && (
                    <div className="flex items-center justify-start p-3 rounded-lg">
                      <div className="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0 mr-2">
                        <img className="w-10 h-10 rounded-full" src="/public/img/robot.jpg" alt="Gemini AI" />
                      </div>
                      <span className="jumping-dots">
                        <span className="dot-1"></span>
                        <span className="dot-2"></span>
                        <span className="dot-3"></span>
                      </span>
                    </div>
                  )}
                </div>
              </div>
            </div>
            <div className="flex items-center rounded-xlw-full pb-36">
              <input
                type="text"
                value={input}
                onChange={(e) => setInput(e.target.value)}
                onKeyDown={handleKeyDown}
                className="flex-grow border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10"
                placeholder="Type your message here..."
              />
              <button
                onClick={handleSend}
                className="ml-4 bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-1 rounded-xl"
              >
                Send
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
