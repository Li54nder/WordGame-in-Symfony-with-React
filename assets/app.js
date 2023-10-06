import WordGame from './components/WordGame';
import React, { useState, createContext } from 'react'
import ReactDOM from "react-dom/client";

export const WordContext = createContext();

export default function App() {
  const [words, addWords] = useState([]);

  return (
    <WordContext.Provider value={{
      words,
      addWords
    }}>
      <WordGame/>
    </WordContext.Provider>
  )
}

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<App />)