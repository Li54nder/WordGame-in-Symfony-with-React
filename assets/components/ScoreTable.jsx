import React, { useContext } from 'react'
import { WordContext } from '../app';
import { Table, TableBody, TableCell, TableHead, TableRow } from '@mui/material';

export default function ScoreTable() {
  const context = useContext(WordContext);

  const scoreTable = {
    width: '100%'
  }

  return context.words.length? 
  (
    <div style={scoreTable}>
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Word</TableCell>
            <TableCell align='center'>Score</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {context.words.map((item, i) => (
            <TableRow key={i}>
              <TableCell>{item.word}</TableCell>
              <TableCell id={`score${context.words.length - i}`} align='center'>{item.score}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  ) : 
  (
    <></>
  )
}