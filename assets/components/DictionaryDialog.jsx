import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  IconButton,
  List,
  ListItem,
  ListItemButton,
  ListItemText,
  Paper,
} from '@mui/material';
import CloseIcon from '@mui/icons-material/Close';
import React, { useState } from 'react';

export default function DictionaryDialog({dictionary, openDialog, setOpenDialog}) {
  // const [openDialog, setOpenDialog] = useStateFn(false);

  return (
    <Dialog
      onClose={() => setOpenDialog(false)}
      fullWidth={true}
      maxWidth="xs"
      open={openDialog}
    >
      <DialogTitle sx={{ m: 0, p: 2 }} id="customized-dialog-title">
        Dictionary
      </DialogTitle>
      <IconButton
        aria-label="close"
        onClick={() => setOpenDialog(false)}
        sx={{
          position: 'absolute',
          right: 8,
          top: 8,
        }}
      >
        <CloseIcon />
      </IconButton>

      <DialogContent dividers style={{ padding: '0px 0px 0px 0px' }}>
        <Paper style={{ maxHeight: 200, overflow: 'auto' }}>
          <List>
            {dictionary.map((word, i) => (
              <ListItem disablePadding key={i}>
                <ListItemButton>
                  <ListItemText primary={word.word} />
                </ListItemButton>
              </ListItem>
            ))}
          </List>
        </Paper>
      </DialogContent>

      <DialogActions>
        <Button onClick={() => setOpenDialog(false)}>Close Dialog</Button>
      </DialogActions>
    </Dialog>
  );
}
