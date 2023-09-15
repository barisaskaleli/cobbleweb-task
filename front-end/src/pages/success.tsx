import {Box, Button, Center, Heading, VStack} from '@chakra-ui/react';
import {useRouter} from 'next/router';
import React from 'react';

const SuccessPage: React.FC = () => {
    const router = useRouter();

    const handleGoHome = () => {
        router.push('/login');
    };

    return (
        <Center h="100vh" bg="green.50">
            <VStack spacing={4} p={5} boxShadow="md" bg="white" rounded="md">
                <Heading as="h1" size="2xl" color="green.500">
                    Success!
                </Heading>
                <Box fontSize="lg" textAlign="center">
                    Your account has been created. Please login.
                </Box>
                <Button colorScheme="green" onClick={handleGoHome}>
                    Login
                </Button>
            </VStack>
        </Center>
    );
};

export default SuccessPage;