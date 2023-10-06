import React, { useContext, useEffect, useState } from 'react';
import ScoreTable from './ScoreTable';
import {
  Alert,
  Button,
  Link,
  Snackbar,
  TextField,
  Typography,
} from '@mui/material';
import { WordContext } from '../app';
import axios from 'axios';
import DictionaryDialog from './DictionaryDialog';

export default function WordGame() {
  const context = useContext(WordContext);
  const [word, setWord] = useState('');
  const [dictionary, setDictionary] = useState([]);
  const [openDialog, setOpenDialog] = useState(false);
  const [snackbarMessage, setSnackbarMessage] = useState('');
  const [openSnackbar, setOpenSnackbar] = useState(false);

  const getAllWords = async () => {
    try {
      const response = await axios.get('/api/readAllWords');
      setDictionary(response.data);
    } catch (error) {
      console.error(error);
      setDictionary([]);
    }
  };

  useEffect(() => {
    getAllWords();
  }, []);

  const submitFrom = (event) => {
    event.preventDefault();
    axios
      .post('/api/checkWord', { word })
      .then((response) => {
        context.addWords([response.data, ...context.words]);
      })
      .catch((error) => {
        setSnackbarMessage(error.response.data.msg);
        setOpenSnackbar(true);
      });
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
    alignItems: 'center',
  };
  const form = {
    width: '100%',
    maxWidth: '300px',
    display: 'flex',
    flexDirection: 'column',
    gap: '20px',
  };
  const marginBottom = {
    marginBottom: '15px',
  };
  const alert = {
    width: '100%',
    maxWidth: '300px',
    boxSizing: 'border-box',
  };
  return (
    <div style={page}>
      <div style={content}>
        <Typography variant="h2" style={marginBottom}>
          Word Game
        </Typography>

        <form onSubmit={submitFrom} style={{ ...form, ...marginBottom }}>
          <TextField
            label="Enter your word to play"
            type="text"
            name='word'
            value={word}
            onChange={(e) => setWord(e.target.value)}
          />
          <Button variant="contained" type="submit" disabled={!word.length}>
            Check Word
          </Button>
        </form>

        <Alert style={alert} severity="info" color="info">
          Only words from our dictionary are allowed! Click{' '}
          <Link onClick={() => setOpenDialog(true)}>here</Link> to see
          dictionary.
        </Alert>

        <Snackbar
          open={openSnackbar}
          anchorOrigin={{ vertical: 'bottom', horizontal: 'center' }}
          autoHideDuration={4000}
          onClose={() => setOpenSnackbar(false)}
          message={snackbarMessage}
        />

        <DictionaryDialog dictionary={dictionary} openDialog={openDialog} setOpenDialog={setOpenDialog} />

        <ScoreTable />
      </div>
    </div>
  );
}
