import React, {FC, useState} from 'react';
import {useForm, Controller, SubmitHandler} from "react-hook-form";
import {yupResolver} from '@hookform/resolvers/yup';
import * as yup from 'yup';
import {
    Box,
    Input,
    Button,
    FormControl,
    FormLabel,
    FormErrorMessage,
    VStack,
    HStack,
    Image,
    Text,
    useToast
} from "@chakra-ui/react";
import axios from 'axios';
import {useRouter} from "next/router";

type IFormInput = {
    firstName: string;
    lastName: string;
    email: string;
    password: string;
    photos: FileList
};

const schema = yup.object().shape({
    firstName: yup.string().min(2, 'Minimum 2 characters').max(25, 'Maximum 25 characters').required('Required'),
    lastName: yup.string().min(2, 'Minimum 2 characters').max(25, 'Maximum 25 characters').required('Required'),
    email: yup.string().email('Invalid email').required('Required'),
    password: yup
        .string()
        .min(6, 'Minimum 6 characters')
        .max(50, 'Maximum 50 characters')
        .matches(/^(?=.*[0-9]).*$/, 'Must contain at least one number')
        .required('Required'),
    photos: yup
        .mixed()
        .required('At least one photo is required')
        .test(
            'filesLength',
            'At least 4 photos should be selected',
            (value) => value instanceof FileList && value.length >= 4,
        ),
});

const RegisterPage: FC = () => {
    const {control, handleSubmit, formState: {errors, isSubmitting}} = useForm<IFormInput>({
        // @ts-ignore
        resolver: yupResolver(schema)
    });
    const [selectedFiles, setSelectedFiles] = useState<File[]>([]);
    const [apiError, setApiError] = useState<string | null>(null);
    const router = useRouter();
    const toast = useToast()
    const [loading, setIsLoading] = useState(false);

    const onSubmit: SubmitHandler<IFormInput> = async (data) => {
        setIsLoading(true)

        const formData = new FormData();

        formData.append('first_name', data.firstName);
        formData.append('last_name', data.lastName);
        formData.append('email', data.email);
        formData.append('password', data.password);

        Array.from(data.photos).forEach((photo, index) => {
            formData.append('photos[]', photo, photo.name);
        });

        const response = await axios.post(`${process.env.NEXT_PUBLIC_API_ENDPOINT}/api/users/register`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        }).then((response) => {
            toast({
                title: 'Account created',
                description: "Account created successfully. You'll be redirected to the login page within 3 seconds.",
                status: 'success',
                position: 'top-right',
                duration: 9000,
                isClosable: true,
            })

            setTimeout(() => {
                router.push('/success');
            }, 3000)

        }).catch((error) => {
            if (error.response) {
                if (error.response.status === 400) {
                    handleErrors(error.response.data.response)

                    toast({
                        title: 'Validation error',
                        description: apiError,
                        status: 'error',
                        position: 'top-right',
                        duration: 9000,
                        isClosable: true,
                    })
                }
            }
        });

        setIsLoading(false)
    };

    const handleErrors = async (errors: any) => {
        const errorMessages = Object.entries(errors).map(
            ([key, message]) => `${key}: ${message}`
        );

        setApiError(errorMessages.join('\n'));
    }

    return (
        <Box width="500px" margin="auto" mt={100} rounded={'lg'} boxShadow={'lg'} p={8} mb={50}>
            <form onSubmit={handleSubmit(onSubmit)}>
                <VStack spacing={4}>
                    <FormControl isInvalid={!!errors.firstName?.message}>
                        <FormLabel>First Name</FormLabel>
                        <Controller
                            name="firstName"
                            control={control}
                            defaultValue=""
                            render={({field}) => <Input {...field} />}
                        />
                        <FormErrorMessage>{String(errors.firstName?.message)}</FormErrorMessage>
                    </FormControl>

                    <FormControl isInvalid={!!errors.lastName?.message}>
                        <FormLabel>Last Name</FormLabel>
                        <Controller
                            name="lastName"
                            control={control}
                            defaultValue=""
                            render={({field}) => <Input {...field} />}
                        />
                        <FormErrorMessage>{String(errors.lastName?.message)}</FormErrorMessage>
                    </FormControl>

                    <FormControl isInvalid={!!errors.email?.message}>
                        <FormLabel>Email</FormLabel>
                        <Controller
                            name="email"
                            control={control}
                            defaultValue=""
                            render={({field}) => <Input {...field} />}
                        />
                        <FormErrorMessage>{String(errors.email?.message)}</FormErrorMessage>
                    </FormControl>

                    <FormControl isInvalid={!!errors.password?.message}>
                        <FormLabel>Password</FormLabel>
                        <Controller
                            name="password"
                            control={control}
                            defaultValue=""
                            render={({field}) => <Input type="password" {...field} />}
                        />
                        <FormErrorMessage>{String(errors.password?.message)}</FormErrorMessage>
                    </FormControl>

                    <FormControl isInvalid={!!errors.photos?.message}>
                        <FormLabel>Photos</FormLabel>
                        <Controller
                            name="photos"
                            control={control}
                            defaultValue={undefined}
                            render={({field}) => (
                                <Input
                                    type="file"
                                    multiple
                                    value={undefined}
                                    id={'actual-btn'}
                                    hidden={true}
                                    onChange={(e) => {
                                        field.onChange(e.target.files);
                                        setSelectedFiles(Array.from(e.target.files!));
                                    }}
                                />
                            )}
                        />
                        <FormErrorMessage>{String(errors.photos?.message)}</FormErrorMessage>

                        <label htmlFor="actual-btn" style={{display: "block"}}>
                            <Button mt={4} colorScheme="gray" type="button" w={'100%'}
                                    onClick={() => document.getElementById('actual-btn').click()}>
                                Choose Photos
                            </Button>
                        </label>

                        {selectedFiles.length > 0 && (
                            <HStack spacing={4} wrap="wrap">
                                {selectedFiles.map((file, index) => (
                                    <Box key={index} position="relative">
                                        <Image
                                            src={URL.createObjectURL(file)}
                                            alt={`Uploaded ${index}`}
                                            boxSize="100px"
                                            objectFit="cover"
                                        />
                                        <Text position="absolute" bottom="4px" left="4px" color="white">
                                            {file.name}
                                        </Text>
                                    </Box>
                                ))}
                            </HStack>
                        )}
                    </FormControl>

                    <Button mt={4} colorScheme="teal" type="submit" w={'100%'} isLoading={loading}>
                        Register
                    </Button>
                </VStack>
            </form>
        </Box>
    );
};

export default RegisterPage;
