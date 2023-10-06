import WordGame from './components/WordGame';
import React, { useState, createContext, useEffect } from 'react'
import ReactDOM from "react-dom/client";
import axios from 'axios';

export const WordContext = createContext();

export default function App() {
  const [words, addWords] = useState([]);
  // useEffect(() => {
  //   axios.get('/api/readAllWords').then( res => {
  //     console.log(res);
  //     addWords(res.data);
  //   }).catch(err => {
  //     console.log(err);
  //     addWords([]);
  //   })
  // }, [])

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