import React, {useState} from 'react';
import {
    Box,
    Button,
    FormControl,
    FormLabel,
    Input,
    VStack,
    useToast, FormErrorMessage,
} from '@chakra-ui/react';
import {useForm, SubmitHandler} from 'react-hook-form';
import axios from 'axios';
import Cookies from 'js-cookie';
import {useRouter} from "next/router";

type Inputs = {
    email: string;
    password: string;
};

const LoginPage: React.FC = () => {
    const {register, handleSubmit, formState: {errors}} = useForm<Inputs>();
    const router = useRouter();
    const toast = useToast();
    const [loading, setIsLoading] = useState(false);

    const onSubmit: SubmitHandler<Inputs> = async (data) => {
        setIsLoading(true);

        const response = await axios.post(`${process.env.NEXT_PUBLIC_API_ENDPOINT}/api/users/login`, data).then((response) => {
            if (response.data.token) {
                Cookies.set('token', response.data.token);

                toast({
                    title: 'Successfully logged in',
                    description: "You'll be redirected to the profile page within 3 seconds.",
                    status: 'success',
                    position: 'top-right',
                    duration: 9000,
                    isClosable: true,
                })

                setTimeout(() => {
                    router.push('/profile');
                }, 3000)
            }
        }).catch((error) => {
            if (error.response) {
                if (error.response.status === 401) {

                    toast({
                        title: 'Unauthorized',
                        description: error.response.data.message,
                        status: 'error',
                        position: 'top-right',
                        duration: 9000,
                        isClosable: true,
                    })
                }
            }
        });

        setIsLoading(false);
    };

    return (
        <Box width="500px" margin="auto" mt={100} rounded={'lg'} boxShadow={'lg'} p={8} mb={50}>
            <form onSubmit={handleSubmit(onSubmit)}>
                <VStack spacing={4}>
                    <FormControl isInvalid={!!errors.email}>
                        <FormLabel>Email</FormLabel>
                        <Input
                            id="email"
                            type="email"
                            {...register('email', {
                                required: 'Email is required',
                                pattern: {
                                    value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i,
                                    message: 'Invalid email address',
                                },
                            })}
                        />
                    </FormControl>
                    <FormErrorMessage>{String(errors.email?.message)}</FormErrorMessage>

                    <FormControl isInvalid={!!errors.password}>
                        <FormLabel>Password</FormLabel>
                        <Input
                            id="password"
                            type="password"
                            {...register('password', {required: 'Password is required'})}
                        />
                    </FormControl>
                    <FormErrorMessage>{String(errors.password?.message)}</FormErrorMessage>

                    <Button mt={4} colorScheme="teal" type="submit" w={'100%'} isLoading={loading}>
                        Login
                    </Button>
                </VStack>
            </form>
        </Box>
    );
};

export default LoginPage;
