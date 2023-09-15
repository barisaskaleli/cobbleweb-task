import {createSlice, createAsyncThunk} from '@reduxjs/toolkit';
import axios from 'axios';

interface UserState {
    fullName: string;
    avatar: string;
    photos: string[];
    status: 'idle' | 'loading' | 'succeeded' | 'failed';
    error: string | null;
}

const initialState: UserState = {
    fullName: '',
    avatar: '',
    photos: [],
    status: 'idle',
    error: null,
};

export const fetchUserProfile = createAsyncThunk('user/fetchUserProfile', async (token: string) => {
    const response = await axios.get(`${process.env.NEXT_PUBLIC_API_ENDPOINT}/api/users/me`, {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
    return response.data;
});

const userSlice = createSlice({
    name: 'user',
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(fetchUserProfile.pending, (state) => {
                state.status = 'loading';
            })
            .addCase(fetchUserProfile.fulfilled, (state, action) => {
                state.status = 'succeeded';
                state.fullName = action.payload.fullName;
                state.avatar = action.payload.avatar;
                state.photos = action.payload.photos;
            })
            .addCase(fetchUserProfile.rejected, (state, action) => {
                state.status = 'failed';
                state.error = action.error.message || null;
            });
    },
});

export default userSlice.reducer;