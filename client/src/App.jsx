// src/App.js
import React, { useState } from 'react';
import { Link2, Copy, ExternalLink } from 'lucide-react';

function App() {
  const [url, setUrl] = useState('');
  const [shortUrl, setShortUrl] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const API_BASE_URL = 'http://localhost:8000';

  const shortenUrl = async () => {
    if (!url) {
      setError('Please enter a URL');
      return;
    }

    try {
      setLoading(true);
      setError('');
      
      const response = await fetch(`${API_BASE_URL}/shorten`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ url }),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error || 'Failed to shorten URL');
      }

      setShortUrl(`${API_BASE_URL}/${data.shortCode}`);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const copyToClipboard = async () => {
    try {
      await navigator.clipboard.writeText(shortUrl);
    } catch (err) {
      console.error('Failed to copy:', err);
    }
  };

  return (
    <div className="min-h-screen bg-gray-100 py-12 px-4">
      <div className="max-w-md mx-auto">
        <div className="bg-white rounded-lg shadow-md">
          <div className="p-6">
            <h1 className="text-2xl font-bold mb-6 flex items-center justify-center gap-2">
              <Link2 className="h-6 w-6" />
              URL Shortener
            </h1>

            <div className="space-y-4">
              <div className="flex gap-2">
                <input
                  type="url"
                  placeholder="Enter your URL here"
                  value={url}
                  onChange={(e) => setUrl(e.target.value)}
                  className="flex-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <button
                  onClick={shortenUrl}
                  disabled={loading}
                  className="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:opacity-50"
                >
                  {loading ? 'Shortening...' : 'Shorten URL'}
                </button>
              </div>

              {error && (
                <div className="p-4 text-red-700 bg-red-100 rounded-md">
                  {error}
                </div>
              )}

              {shortUrl && (
                <div className="mt-4 p-4 bg-gray-50 rounded-md">
                  <div className="flex items-center justify-between gap-2">
                    <a
                      href={shortUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-blue-600 hover:text-blue-800 flex items-center gap-1"
                    >
                      {shortUrl}
                      <ExternalLink className="h-4 w-4" />
                    </a>
                    <button
                      onClick={copyToClipboard}
                      className="p-2 hover:bg-gray-200 rounded-md"
                      title="Copy to clipboard"
                    >
                      <Copy className="h-4 w-4" />
                    </button>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default App;