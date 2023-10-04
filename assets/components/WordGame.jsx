import React, { useContext, useState } from 'react';
import ScoreTable from './ScoreTable';
import { Button, TextField, Typography } from '@mui/material';
import { WordContext } from '../app';

function scoreWord(word) {
  let isPalindrome = true;
  let isAlmostPalindrome = true;
  let tryInOtherDirection = false;
  const letters = new Set();
  let i = 0;
  for(let letter of word) {
    i++;
    letters.add(letter);

    if(isPalindrome && (i - 1) < (word.length / 2) && letter !== word[word.length - i]) {
      isPalindrome = false;
      i++;
    }
    
    if(!isPalindrome && isAlmostPalindrome && (i - 1) <= (word.length / 2) && letter !== word[word.length - i]){
      if(tryInOtherDirection) {
        isAlmostPalindrome = false;
      }
      tryInOtherDirection = true;
      i = i - 2;
    }
  }

  return letters.size + (isPalindrome? 3 : 0) + (!isPalindrome && isAlmostPalindrome? 2 : 0);
}

export default function WordGame() {
  const context = useContext(WordContext);
  const [word, setWord] = useState('');

  const submitFrom = (event) => {
    event.preventDefault();
    const newWord = {
      word,
      score: scoreWord(word)
    }
    context.addWord([newWord, ...context.words])
  };

  const page = {
    width: '100%',
    display: 'flex',
    justifyContent: 'center',
  };
  const content = {
    width: '100%',
    maxWidth: '1000px',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center'
  };
  const form = {
    width: '100%',
    maxWidth: '300px',
    display: 'flex',
    flexDirection: 'column',
    gap: '20px'
  }
  const marginBottom = {
    marginBottom: '15px'
  };

  return (
    <div style={page}>
      <div style={content}>
        <Typography variant="h2" style={marginBottom}>Word Game</Typography>

        <form onSubmit={submitFrom} style={{...form, ...marginBottom}}>
          <TextField label='Enter your word to play' type='text' value={word} onChange={(e) => setWord(e.target.value)}/>
          <Button variant='contained' type='submit'>Check Word</Button>
        </form>

        <ScoreTable />
      </div>
    </div>
  );
}
